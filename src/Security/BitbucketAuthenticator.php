<?php
// src/Security/MyGitHubAuthenticator.php
namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\BitbucketClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use GuzzleHttp\Client;

class BitbucketAuthenticator extends SocialAuthenticator
{
	private $clientRegistry;
	private $em;
	private $router;
	
	public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
	{
		$this->clientRegistry = $clientRegistry;
		$this->em = $em;
		$this->router = $router;
	}
	
	public function supports(Request $request)
	{
		// continue ONLY if the current ROUTE matches the check ROUTE
		return $request->attributes->get('_route') === 'connect_bitbucket_check';
	}
	
	public function getCredentials(Request $request)
	{
		// this method is only called if supports() returns true
		
		// For Symfony lower than 3.4 the supports method need to be called manually here:
		// if (!$this->supports($request)) {
		//     return null;
			// }
			
		return $this->fetchAccessToken($this->getBitbucketClient());
	}
	
	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		/** @var FacebookUser $facebookUser */
		$bitbucketUser = $this->getBitbucketClient()->fetchUserFromToken($credentials);
		
		/* bit bucket doesnt suply the email adres in the return so we need te maken an aditional api call */
		/** @var \GuzzleHttp\Client $client */
		$client = new Client(['base_uri' => 'https://api.bitbucket.org/', 'headers'=>['Authorization'=>'Bearer '.$credentials]]);
		$response = $client->get('/2.0/user/emails');		
		$response = json_decode ($response->getBody(), true);
				
		$new = array_filter($response['values'], function ($var) {
			return ($var['is_primary'] == true);
		});
					
		$email = $new[0]['email'];
		
		// 1) have they logged in with Bitbucket before? Easy!
		$existingUser = $this->em->getRepository(User::class)->findOneBy(['bitbucketId' => $bitbucketUser->getId()]);
		if ($existingUser) {
			// if the user user is already connected we just want to update the security token
			$existingUser->setBitbucketToken($credentials);
			$this->em->persist($existingUser);
			$this->em->flush();
			
			return $existingUser;
		}
		
		// We need an email adres for the following staps so lets check if we have that
		if(!$email){
			// this exception ultimately generates a 500 status error
			throw new \Exception('We could not determine your email adres');
		}
		
		// 2) do we have a matching user by email?
		$user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
		
		// 3) Maybe you just want to "register" them by creating
		// a User object
		if(!$user){
			$user = New User;
		}
		$user->setBitbucketId($bitbucketUser->getId());
		$user->setBitbucketToken($credentials);
		$user->setEmail($email);
		
		if(!$user->getUsername()){
			$user->setUsername($bitbucketUser->getUsername());
		}
		if(!$user->getName()){
			$user->setName($bitbucketUser->getName());
		}
		
		/* @todo we should send an email here */
		$this->em->persist($user);
		$this->em->flush();
		
		return $user;
	}
	
	/**
	 * @return BitbucketClient
	 */
	private function getBitbucketClient()
	{
		return $this->clientRegistry->getClient('bitbucket');
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		// change "app_homepage" to some route in your app
		$targetUrl = $this->router->generate('app_user_index');
		
		return new RedirectResponse($targetUrl);
		
		// or, on success, let the request continue to be handled by the controller
		//return null;
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$message = strtr($exception->getMessageKey(), $exception->getMessageData());
		
		return new Response($message, Response::HTTP_FORBIDDEN);
	}
	
	/**
	 * Called when authentication is needed, but it's not sent.
	 * This redirects to the 'login'.
	 */
	public function start(Request $request, AuthenticationException $authException = null)
	{
		return new RedirectResponse(
				'/connect/', // might be the site, where users choose their oauth provider
				Response::HTTP_TEMPORARY_REDIRECT
				);
	}
	
}