<?php

class Friends_FriendManagerModel extends ShoppingwithfriendsFriendsBaseModel
{
    public function add($username, $friendUser)
    {
        // Make sure the username and friend are set
        if (!isset($username) || !isset($friendUser))
        {
            return 1;
        }

        // Try and find a user in the database matching the requesting user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a user
        if (!isset($user))
        {
            return 1;
        }

        // Try and find a user in the database matching the friend user
        $friend = UserQuery::create()->findOneByUsername($friendUser);

        // Make sure the friend exists
        if (!isset($friend))
        {
            return 2;
        }

        // Try and find a friendship where the user is friend1 and the friend is friend2
        $friendship = FriendshipQuery::create()->filterByFriend1($user->getUserId())->filterByFriend2($friend->getUserId())->findOne();

        // If we found a friendship, then the users are already friends
        if (isset($friendship))
        {
            return 3;
        }

        // If we did not find a friendship, check again with the friend1 and friend2 fields reversed
        $friendship = FriendshipQuery::create()->filterByFriend1($friend->getUserId())->filterByFriend2($user->getUserId())->findOne();

        // If we found a friendship, then the users are already friends
        if (isset($friendship))
        {
            return 3;
        }

        // Otherwise, the users are both valid and a friendship does not already exist, go ahead and create one
        $friendship = new Friendship();
        $friendship->setInviteDate(date("Y-m-d H:i:s"));
        $friendship->setFriend1($user->getUserId());
        $friendship->setFriend2($friend->getUserId());
        $friendship->save();

        return 0;
    }

    public function remove($username, $friendUser)
    {
        // Make sure the username and friend are set
        if (!isset($username) || !isset($friendUser))
        {
            return 1;
        }

        // Try and find a user in the database matching the requesting user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a user
        if (!isset($user))
        {
            return 1;
        }

        // Try and find a user in the database matching the friend user
        $friend = UserQuery::create()->findOneByUsername($friendUser);

        // Make sure the friend exists
        if (!isset($friend))
        {
            return 2;
        }

        // Try and find a friendship where the user is friend1 and the friend is friend2
        $friendship = FriendshipQuery::create()->filterByFriend1($user->getUserId())->filterByFriend2($friend->getUserId())->findOne();

        // If we found a friendship, go ahead and try and delete it, then return the status of the deletion
        if (isset($friendship))
        {
            $friendship->delete();
            if ($friendship->isDeleted())
            {
                return 0;
            }
            else
            {
                return 3;
            }
        }

        // If we did not find a friendship, check again with the friend1 and friend2 fields reversed
        $friendship = FriendshipQuery::create()->filterByFriend1($friend->getUserId())->filterByFriend2($user->getUserId())->findOne();

        // If we found a friendship, go ahead and try and delete it, then return the status of the deletion
        if (isset($friendship))
        {
            $friendship->delete();
            if ($friendship->isDeleted())
            {
                return 0;
            }
            else
            {
                return 3;
            }
        }

        // Otherwise, no friendship exists between the two users, so we don't have one to delete
        return 4;
    }


    public function getList($username)
    {
        // Make sure the username is set
        if (!isset($username))
        {
            return array();
        }

        // Try and find a user in the database matching this user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a user
        if (!isset($user))
        {
            return array();
        }

        // Get a list of all of the friends in which user is friend1
        $friends1 = FriendshipQuery::create()->findByFriend1($user->getUserId());

        // Get a list of all of the friends in which user is friend2
        $friends2 = FriendshipQuery::create()->findByFriend2($user->getUserId());

        // Create an array to hold our friend objects
        // TODO: Actually create a friend class to hold these objects, for now they are just the names as a string
        $friends = array();
        foreach ($friends1 as $friendship)
        {
            $friend = UserQuery::create()->findPk($friendship->getFriend2());
            array_push($friends, $friend->getFirstName() . " " . $friend->getLastName() . "(" . $friend->getUsername() . ")");
        }
        foreach ($friends2 as $friendship)
        {
            $friend = UserQuery::create()->findPk($friendship->getFriend1());
            array_push($friends, $friend->getFirstName() . " " . $friend->getLastName() . "(" . $friend->getUsername() . ")");
        }

        return $friends;
    }

    public function get($username, $friendUser)
    {
        // Make sure the username is set
        if (!isset($username))
        {
            return 1;
        }

        // Try and find a user in the database matching this user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a username
        if (!isset($user))
        {
            return 1;
        }

        // Try and find a user in the database matching the friend user
        $dbFriend = UserQuery::create()->findOneByUsername($friendUser);

        // Make sure the friend exists
        if (!isset($dbFriend))
        {
            return 2;
        }

        // Try and find a friendship where the user is friend1 and the friend is friend2
        $friendship = FriendshipQuery::create()->filterByFriend1($user->getUserId())->filterByFriend2($dbFriend->getUserId())->findOne();

        // If we did not find a friendship, try and find one with friend1 and friend2 reversed
        if (!isset($friendship))
        {
            $friendship = FriendshipQuery::create()->filterByFriend1($dbFriend->getUserId())->filterByFriend2($user->getUserId())->findOne();

            // If we still did not find a friendship, then the users are not actually friends
            if (!isset($friendship))
            {
                return 3;
            }
        }

        // Setup all of the variables that our friend will need
        $friendData = array();
        $friendData['username'] = $dbFriend->getUsername();
        $friendData['firstName'] = $dbFriend->getFirstName();
        $friendData['lastName'] = $dbFriend->getLastName();
        $friendData['email'] = $dbFriend->getEmail();

        // Retrieve a list of all the ratings for this user
        $ratings = SaleRatingQuery::create()->filterByPostingUserId($dbFriend->getUserId());

        // Get the average of all the ratings
        $total = 0;
        $count = 0;
        foreach ($ratings as $rating)
        {
            $total += $rating->getRating();
            $count++;
        }
        if ($count > 0)
        {
            $friendData['rating'] = $total / $count;
        }
        else
        {
            $friendData['rating'] = 0;
        }

        // Get the number of sale reports the friend has posted
        $friendData['reportCount'] = SaleQuery::create()->filterByUserId($dbFriend->getUserId())->count();
        error_log("Friend data: " . print_r($friendData, true));

        // We are friends and our friend is a proper user, so go ahead and create a new friend object to return to the user
        $friend = $this->getContext()->getModel('Friend', 'Friends');
        $friend->fromArray($friendData);
        return $friend;
    }

}

?>
