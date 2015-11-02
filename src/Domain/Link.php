<?php

namespace WebLinks\Domain;

class Link 
{
    /**
     * Link id.
     *
     * @var integer
     */
    private $id;

    /**
     * Link title.
     *
     * @var string
     */
    private $title;

    /**
     * Link url.
     *
     * @var string
     */
    private $url;

    /**
     * Link user
     *
     * @var \WebLinks\Domain\User
     */
    private $user;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \WebLinks\Domain\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
