<?php

namespace Models;

class Quiz
{
    private $name;
    private $questions;
    private $private;
    private $creationDate;
    private $time;

    public function __construct($name, $questions, $private, $creationDate, $time)
    {
        $this->name = $name;
        $this->questions = $questions;
        $this->private = $private;
        $this->creationDate = $creationDate;
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function isPrivate()
    {
        return $this->private? 1 : 0;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    public function getQuestionsCount() {
        return count($this->questions);
    }
}