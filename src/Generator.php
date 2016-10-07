<?php
namespace ArekvanSchaijk\BitbucketServerToSatisGenerator;

use ArekvanSchaijk\BitbucketServerClient\Api;
use ArekvanSchaijk\BitbucketServerClient\Api\Entity\Project;
use ArekvanSchaijk\BitbucketServerClient\Api\Entity\Repository;

/**
 * Class Generator
 * @package ArekvanSchaijk\BitbucketServerToSatisGenerator
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class Generator
{

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var Project[]
     */
    protected $bitbucketProjects = [];

    /**
     * @var Repository[]
     */
    protected $bitbucketRepositories = [];

    /**
     * Generator constructor.
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Adds an entire Bitbucket Project
     *
     * @param Project $project
     * @return void
     */
    public function addBitbucketProject(Project $project)
    {
        $this->bitbucketProjects[] = $project;
    }

    /**
     * Adds a Bitbucket Repository
     *
     * @param Repository $repository
     * @return void
     */
    public function addBitbucketRepository(Repository $repository)
    {
        $this->bitbucketRepositories[] = $repository;
    }

    /**
     * Build
     *
     * @param array $scheme
     * @return array
     */
    public function build(array $scheme)
    {
        $json = [
            'repositories' => []
        ];
        if ($this->bitbucketProjects) {
            foreach ($this->bitbucketProjects as $project) {
                if (($repositories = $project->getRepositories())) {
                    /* @var $repository Repository */
                    foreach ($repositories as $repository) {
                        $json['repositories'][] = ['type' => 'vcs', 'url' => $repository->getSshCloneUrl()];
                    }
                }
            }
        }
        if ($this->bitbucketRepositories) {
            /* @var $repository Repository */
            foreach ($this->bitbucketRepositories as $repository) {
                $json['repositories'][] = ['type' => 'vcs', 'url' => $repository->getSshCloneUrl()];
            }
        }
        return array_merge($scheme, $json);

    }

    /**
     * Write
     *
     * @param string $absoluteFilePath
     * @return void
     */
    public function write($absoluteFilePath)
    {

    }

}