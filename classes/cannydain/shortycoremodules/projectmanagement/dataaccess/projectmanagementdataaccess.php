<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\GUIDManagerConsumer;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\IterationStatsModel;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStorySearch;

class ProjectManagementDataAccess implements DataMapperConsumer, GUIDManagerConsumer
{
    const OBJECT_PROJECT = '\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\Project';
    const OBJECT_USER_STORY = '\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\UserStory';
    const OBJECT_USER_ITERATION = '\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Models\\Iteration';

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @param $project
     * @return Iteration[]
     */
    public function getAllIterations($project)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_USER_ITERATION, array
        (
            'project' => $project
        ), 'start ASC');
    }

    /**
     * @param $project
     * @return IterationStatsModel[]
     */
    public function getIterationStats($project)
    {
        $ret = array();

        foreach ($this->getAllIterations($project) as $iteration)
        {
            /**
             * @var UserStory[] $completedStories
             * @var UserStory[] $incompleteStories
             */
            $completedStories = $this->getStoriesByCompletionDate($project, $iteration->getIterationStart(), $iteration->getIterationEnd());
            $incompleteStories = array();

            if ($iteration->getIterationStart() <= time() && $iteration->getIterationEnd() >= time())
                $incompleteStories = $this->getIncompleteStories($project);

            $effortExpended = 0;
            $effortRemaining = 0;

            foreach ($completedStories as $story)
                $effortExpended += $story->getEstimate();

            foreach ($incompleteStories as $story)
                $effortRemaining += $story->getEstimate();

            $stats = new IterationStatsModel();

            $stats->setIteration($iteration);
            $stats->setEffort($effortExpended);
            $stats->setRemainingEffort($effortRemaining);

            $ret[] = $stats;
        }

        return $ret;
    }

    /**
     * @param $projectID
     * @return Iteration
     */
    public function getCurrentIteration($projectID)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_ITERATION, array
        (
            'start  < :now',
            '(end > :now || end IS NULL)',
            'project = :project'
        ), array
        (
            'now' => date('Y-m-d H:i:s'),
            'project' => $projectID
        )));
    }

    /**
     * @param $project
     * @param $startDate
     * @param $endDate
     * @return UserStory[]
     */
    public function getStoriesByCompletionDate($project, $startDate, $endDate)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_STORY, array
        (
            'completed >= :start',
            'completed <= :end',
            'project = :project'
        ), array
        (
            'start' => date('Y-m-d H:i:s',$startDate),
            'end' => date('Y-m-d H:i:s', $endDate),
            'project' => $project
        ));
    }

    /**
     * @param $project
     * @return UserStory[]
     */
    public function getIncompleteStories($project)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_STORY, array
        (
            'project = :project',
            '(completed < started OR completed IS NULL)',
            'status IN (:status_1, :status_2, :status_3, :status_4, :status_5)',
        ), array
        (
            'status_1' => UserStory::STATUS_TO_BE_DEVELOPED,
            'status_2' => UserStory::STATUS_BEING_DEVELOPED,
            'status_3' => UserStory::STATUS_TO_BE_TESTED,
            'status_4' => UserStory::STATUS_BEING_TESTED,
            'status_5' => UserStory::STATUS_TO_BE_SIGNED_OFF,
            'project' => $project
        ));
    }

    /**
     * @param $project
     * @param $startDate
     * @param $endDate
     * @return UserStory[]
     */
    public function getIncompleteStoriesByStartDate($project, $startDate, $endDate)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_STORY, array
        (
            'started >= :start',
            'started <= :end',
            'project = :project',
            'completed < started',
        ), array
        (
            'start' => date('Y-m-d H:i:s',$startDate),
            'end' => date('Y-m-d H:i:s', $endDate),
            'project' => $project
        ));
    }

    /**
     * @param $id
     * @return Iteration
     */
    public function getIteration($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_USER_ITERATION, array('id' => $id));
    }

    /**
     * @param $project
     * @param null $count
     * @return UserStory[]
     */
    public function getRecommendedStories($project, $count = null)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_STORY, array
        (
            'project = :project',
            'status IN (:status_1, :status_2, :status_3, :status_4)',
        ), array
        (
            'project' => $project,
            'status_1' => UserStory::STATUS_TO_BE_DEVELOPED,
            'status_2' => UserStory::STATUS_BEING_DEVELOPED,
            'status_3' => UserStory::STATUS_TO_BE_TESTED,
            'status_4' => UserStory::STATUS_BEING_TESTED,
        ), 'weight DESC, priority DESC', 0, $count, array
        (
            'priority / estimate as weight'
        ));

        // high priority, low estimate
        // priority / estimate
    }

    public function saveIteration(Iteration $iteration)
    {
        $this->_datamapper->saveObject($iteration);
    }

    /**
     * @return Project[]
     */
    public function getAllProjects()
    {
        return $this->_datamapper->getAllObjects(self::OBJECT_PROJECT);
    }

    public function getProjectGUID($id)
    {
        return $this->_guids->getGUID(self::OBJECT_PROJECT, $id);
    }

    public function getStoryGUID($id)
    {
        return $this->_guids->getGUID(self::OBJECT_USER_STORY, $id);
    }

    /**
     * @param UserStorySearch $search
     * @return UserStory[]
     */
    public function searchStories(UserStorySearch $search)
    {
        $clauses = array();
        $parameters = array();

        if ($search->getProject() > 0)
        {
            $clauses[] = 'project = :project';
            $parameters['project'] = $search->getProject();
        }

        if (count($search->getStatuses()) > 0)
        {
            $clauses[] = 'status IN (:status_'.implode(', :status_', $search->getStatuses()).')';
            foreach ($search->getStatuses() as $status)
                $parameters['status_'.$status] = $status;
        }

        if ($search->getSearchTerm() != '')
        {
            $clauses[] = 'name LIKE :name';
            $parameters['name'] = '%'.$search->getSearchTerm().'%';
        }

        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER_STORY, $clauses, $parameters, 'priority DESC');
    }

    public function deleteStoryByID($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_USER_STORY, array('id' => $id));
    }

    public function deleteProjectByID($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_PROJECT, array('id' => $id));
    }

    /**
     * @param null $projectID
     * @return UserStory[]
     */
    public function getAllUserStoriesByProject($projectID)
    {
        /**
         * @var UserStory[] $stories
         */
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_USER_STORY, array('project' => $projectID), 'priority DESC');
    }

    /**
     * @param $id
     * @return UserStory
     */
    public function getUserStory($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_USER_STORY, array('id' => $id));
    }

    /**
     * @param $id
     * @return Project
     */
    public function getProject($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_PROJECT, array('id' => $id));
    }

    public function saveProject(Project $project)
    {
        $this->_datamapper->saveObject($project);
    }

    public function saveUserStory(UserStory $story)
    {
        $this->_datamapper->saveObject($story);
    }

    public function registerObjects()
    {
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile(dirname(dirname(__FILE__)).'/datadictionary/projectmanagement.json', $this->_datamapper);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }

    public function consumeGUIDManager(GUIDManagerInterface $dependency)
    {
        $this->_guids = $dependency;
    }
}