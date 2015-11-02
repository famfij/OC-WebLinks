<?php

namespace WebLinks\DAO;

use WebLinks\Domain\Link;
use WebLinks\DAO\UserDAO;

class LinkDAO extends DAO 
{
    /**
     * @var \WebLinks\DAO\UserDAO;
     */
    protected $userDAO;

    /**
     * @param \WebLinks\DAO\UserDAO $userDAO
     */
    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    /**
     * Returns a list of all links, sorted by id.
     *
     * @return array A list of all links.
     */
    public function findAll() {
        $sql = "select * from t_link order by link_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns the link according with the id.
     *
     * @param int $id
     * @return array A list of all links.
     */
    public function find($id) {
        $sql = "select * from t_link where link_id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            throw new \Exception("No Link matching id ".$id);
        }
    }

    /**
     * Creates an Link object based on a DB row.
     *
     * @param array $row The DB row containing Link data.
     * @return \WebLinks\Domain\Link
     */
    protected function buildDomainObject($row) {
        $link = new Link();
        $link->setId($row['link_id']);
        $link->setUrl($row['link_url']);
        $link->setTitle($row['link_title']);

        if (array_key_exists('user_id', $row)) {
            $link->setUser($this->userDAO->find($row['user_id']));
        }
        return $link;
    }

    /**
     * @param Link $link
     */
    public function save(Link $link)
    {
        $linkData = array(
            'link_title' => $link->getTitle(),
            'link_url' => $link->getUrl(),
            'user_id' => $link->getUser()->getId(),
        );

        if ($link->getId()) {
            // The link has already been saved : update it
            $this->getDb()->update('t_link', $linkData, array('link_id' => $link->getId()));
        } else {
            // The link has never been saved : insert it
            $this->getDb()->insert('t_link', $linkData);
            // Get the id of the newly created link and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $link->setId($id);
        }
    }
}
