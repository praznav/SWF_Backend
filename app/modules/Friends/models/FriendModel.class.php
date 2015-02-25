<?php

class Friends_FriendModel extends ShoppingwithfriendsFriendsBaseModel
{
    private $username;
    private $firstName;
    private $lastName;
    private $email;
    private $rating;
    private $reportCount;

    public function __construct(array $data = null)
    {
        if (!empty($data))
        {
            $this->fromArray($data);
        }
    }

    public function fromArray(array $data)
    {
        $this->username = $data['username'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->email = $data['email'];
        $this->rating = $data['rating'];
        $this->reportCount = $data['reportCount'];
    }


    public function toArray()
    {
        $data = array();
        $data['username'] = $this->getUsername();
        $data['firstName'] = $this->getFirstName();
        $data['lastName'] = $this->getLastName();
        $data['email'] = $this->getEmail();
        $data['rating'] = $this->getRating();
        $data['reportCount'] = $this->getReportCount();
        return $data;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($newUsername)
    {
        $this->username = $newUsername;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($newFirstName)
    {
        $this->firstName = $newFirstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($newLastName)
    {
        $this->lastName = $newLastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($newEmail)
    {
        $this->email = $newEmail;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($newRating)
    {
        $this->rating = $newRating;
    }

    public function getReportCount()
    {
        return $this->reportCount;
    }

    public function setReportCount($newReportCount)
    {
        $this->reportCount = $newReportCount;
    }
}

?>
