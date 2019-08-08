<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
 

class SecurityController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted('ROLE_USER') != false) {
            return $this->redirectToRoute('dashboard');
        }
    	
       	// get the login error if there is one
    	$error = $authenticationUtils->getLastAuthenticationError();

    	// last username entered by the user
    	$lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();

        $form = $this->_registerForm($user);

        return $this->render('security/index.html.twig', [
            'last_username' => $lastUsername,
        	'error'         => $error,
            'errors'        => array(),
            'form' => $form->createView(),
        ]);
    }

    public function _registerForm($user){
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, 
                [
                    'label' => 'Username',
                    'attr' => ['class' => 'form-control']
                ])
            ->add('email', EmailType::class, 
                [
                    'label' => 'Email',
                    'attr' => ['class' => 'form-control']
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('save', SubmitType::class, ['label' => 'Register','attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        return $form;
    }

    /**
    * @Route("/register", name="register", methods={"POST"})
    */
    public function register(Request $request,ObjectManager $manager, ValidatorInterface $validator, \Swift_Mailer $mailer){

        $user = new User();
        $form = $this->_registerForm($user);
        $errors = array();
        $error = '';

        if ($request->getMethod() =='POST') {
            
            $form->handleRequest($request);
            
            $errors = $validator->validate($user);
            if( $form->isSubmitted() && $form->isValid() ){
               
                if (count($errors) <= 0) {

                    $user = $form->getData();                    
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
                    $user->setUserStatus(0);

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($user);

                    $manager->flush();
                    $userid = $user->getId();

                    $user_id = base64_encode( $this->getParameter('KEY1') . '~' . $userid . '~' . $this->getParameter('KEY2') );

                    $subject = "Welcome to the website";
                    $message = (new \Swift_Message($subject))
                        ->setFrom('inderjit.smartshore@gmail.com')
                        ->setTo($form->get('email')->getData())
                        ->setBody(
                            $this->renderView(
                                // templates/emails/registration.html.twig
                                'emails/registration.html.twig',
                                ['username' => $form->get('username')->getData(), 'userid' => $user_id]
                            ),
                            'text/html'
                        );

                    try {
                      $mailer->send($message);
                      //If the exception is thrown, this text will not be shown
                      echo 'done';
                    }
                    //catch exception
                    catch(Exception $e) {
                      echo 'Message: ' .$e->getMessage();
                    }                     

                    $this->addFlash('success', 'User registered successfully');

                    return $this->redirectToRoute('login');                
                }
            }
        }       


        return $this->render('security/index.html.twig', [
            'error'         => $error,
            'errors'        => $errors,
            'form'          => $form->createView(),
            'last_username' => ''
        ]); 

    }

    /**
    * @Route("/activate/{user_encrypted_id}", name="activate_link", methods={"GET"})
    */
    public function activate($user_encrypted_id){

        if(!isset($user_encrypted_id)){
            $this->addFlash('error', 'Invalid user activation link');
        }else{
            $user_array = explode('~',base64_decode($user_encrypted_id));         

            $manager = $this->getDoctrine()->getManager();            
            $user    = $manager->getRepository(User::class)->find($user_array[1]);

            $user->setUserStatus(1);
            $manager->flush();

            $this->addFlash('success', 'Your account is active now.Please login to continue');            
        }
        return $this->redirectToRoute('login');
    }
}