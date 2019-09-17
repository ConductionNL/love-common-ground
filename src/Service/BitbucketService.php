<?php
// src/Service/AmbtenaarService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Organisation;
use App\Entity\Component; 

class BitbucketService
{
	private $params;
	private $cash;
	private $markdown;
	private $em;
	private $githubToken;
	private $client;
	
	public function __construct(ParameterBagInterface $params, MarkdownParserInterface $markdown, CacheInterface $cache, EntityManagerInterface $em, $githubToken = null)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->markdown = $markdown;
		$this->em = $em;
		$this->githubToken = $githubToken;
		if($this->githubToken){
			$this->client = new Client(['base_uri' => 'https://api.bitbucket.org/2.0/', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
		}
		else{
			$this->client = new Client(['base_uri' => 'https://api.bitbucket.org/2.0/']);
		}
	}
		
	public function getComponentFromBitbucket($fullName)
	{
		$response = $this->client->get('/2.0/repositories/'.$fullName);
		$response = json_decode ($response->getBody(), true);
		
		$component = New Component;
		$component->setName($response['name']);
		$component->setLogo($response['links']['avatar']['href']);
		$component->setBitbucket($response['links']['html']['href']);
		$component->setBitbucketId($response['full_name']);
		
		return $component;
	}
	
	public function getOrganisationFromBitbucket($id)
	{
		$response = $this->client->get('/2.0/teams/'.$id);
		$response = json_decode ($response->getBody(), true);
		
		$organisation = New Organisation;
		$organisation->setName($response['display_name']);
		$organisation->setLogo($response['links']['avatar']['href']);
		$organisation->setBitbucket($response['links']['html']['href']);
		$organisation->setBitbucketId($response['username']);
		
		return $organisation;
	}
	
	// Returns an array of teams for an organisation
	public function getTeamsFromBitbucket($id)
	{
		$response = $this->client->get('/orgs/'.$id.'/teams');
		$responses = json_decode ($response->getBody(), true);
		
		$teams=[];
		foreach($responses as $response){
			$team = New Team;
			$team->setName($response['name']);
			$team->setDescription($response['description']);
			$team->setGit($response['html_url']);
			$team->setGitType('github');
			$team->setGitId($response['id']);
			$teams[] = $team;
		}
		
		return $teams;
	}
	
	
	// Returns an array of repositories for an organisation
	public function getOrganisationRepositories ($id)
	{
		$repositories= [];	
		$response = $this->client->get('/2.0/repositories/'.$id);
		$responses = json_decode ($response->getBody(), true);
		
		foreach($responses['values'] as $repository){
			$repositories[]= [
					"type"=>"bitbucket",
					"link"=>$repository['links']['html']['href'],
					"id"=>$repository['full_name'],
					"name"=>$repository['name'],
					"description"=>$repository['description'],
					"avatar"=>$repository['owner']['avatar']['href']
			];
		}
		
		
		// Lets then remove all te repositories that are already on this platform
		foreach($repositories as $repository){
			
			$components= $this->em->getRepository('App:Component')->findOneBy(array('gitId' => $repository["id"],'gitType' => 'bitbucket'));
			
			if($component){
				$repositories[$key]['commonGroundId'] = $component->getId();
			}
		}
		
		return $repositories;
	}
	
	// Returns an array of repositories for an user
	public function getUserRepositories ($id)
	{
		$repositories= [];
		$response = $this->client->get('/2.0/repositories/'.$id);
		$responses = json_decode ($response->getBody(), true);
		
		foreach($responses['values'] as $repository){
			$repositories[]= [
					"type"=>"bitbucket",
					"link"=>$repository['links']['html']['href'],
					"id"=>$repository['full_name'],
					"name"=>$repository['name'],
					"description"=>$repository['description'],
					"avatar"=>$repository['owner']['avatar']['href']
			];
		}
		
		return $repositories;
	}
}
