<?php

namespace Base;

use \Friendship as ChildFriendship;
use \FriendshipQuery as ChildFriendshipQuery;
use \Exception;
use \PDO;
use Map\FriendshipTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'friendship' table.
 *
 *
 *
 * @method     ChildFriendshipQuery orderByFriendshipId($order = Criteria::ASC) Order by the friendship_id column
 * @method     ChildFriendshipQuery orderByInviteDate($order = Criteria::ASC) Order by the invite_date column
 * @method     ChildFriendshipQuery orderByFriend1($order = Criteria::ASC) Order by the friend1 column
 * @method     ChildFriendshipQuery orderByFriend2($order = Criteria::ASC) Order by the friend2 column
 *
 * @method     ChildFriendshipQuery groupByFriendshipId() Group by the friendship_id column
 * @method     ChildFriendshipQuery groupByInviteDate() Group by the invite_date column
 * @method     ChildFriendshipQuery groupByFriend1() Group by the friend1 column
 * @method     ChildFriendshipQuery groupByFriend2() Group by the friend2 column
 *
 * @method     ChildFriendshipQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFriendshipQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFriendshipQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFriendshipQuery leftJoinUserRelatedByFriend2($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByFriend2 relation
 * @method     ChildFriendshipQuery rightJoinUserRelatedByFriend2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByFriend2 relation
 * @method     ChildFriendshipQuery innerJoinUserRelatedByFriend2($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByFriend2 relation
 *
 * @method     ChildFriendshipQuery leftJoinUserRelatedByFriend1($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByFriend1 relation
 * @method     ChildFriendshipQuery rightJoinUserRelatedByFriend1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByFriend1 relation
 * @method     ChildFriendshipQuery innerJoinUserRelatedByFriend1($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByFriend1 relation
 *
 * @method     \UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFriendship findOne(ConnectionInterface $con = null) Return the first ChildFriendship matching the query
 * @method     ChildFriendship findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFriendship matching the query, or a new ChildFriendship object populated from the query conditions when no match is found
 *
 * @method     ChildFriendship findOneByFriendshipId(string $friendship_id) Return the first ChildFriendship filtered by the friendship_id column
 * @method     ChildFriendship findOneByInviteDate(string $invite_date) Return the first ChildFriendship filtered by the invite_date column
 * @method     ChildFriendship findOneByFriend1(string $friend1) Return the first ChildFriendship filtered by the friend1 column
 * @method     ChildFriendship findOneByFriend2(string $friend2) Return the first ChildFriendship filtered by the friend2 column
 *
 * @method     ChildFriendship[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFriendship objects based on current ModelCriteria
 * @method     ChildFriendship[]|ObjectCollection findByFriendshipId(string $friendship_id) Return ChildFriendship objects filtered by the friendship_id column
 * @method     ChildFriendship[]|ObjectCollection findByInviteDate(string $invite_date) Return ChildFriendship objects filtered by the invite_date column
 * @method     ChildFriendship[]|ObjectCollection findByFriend1(string $friend1) Return ChildFriendship objects filtered by the friend1 column
 * @method     ChildFriendship[]|ObjectCollection findByFriend2(string $friend2) Return ChildFriendship objects filtered by the friend2 column
 * @method     ChildFriendship[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FriendshipQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\FriendshipQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ShoppingWithFriends', $modelName = '\\Friendship', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFriendshipQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFriendshipQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFriendshipQuery) {
            return $criteria;
        }
        $query = new ChildFriendshipQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFriendship|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FriendshipTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FriendshipTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildFriendship A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT FRIENDSHIP_ID, INVITE_DATE, FRIEND1, FRIEND2 FROM friendship WHERE FRIENDSHIP_ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFriendship $obj */
            $obj = new ChildFriendship();
            $obj->hydrate($row);
            FriendshipTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildFriendship|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the friendship_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFriendshipId(1234); // WHERE friendship_id = 1234
     * $query->filterByFriendshipId(array(12, 34)); // WHERE friendship_id IN (12, 34)
     * $query->filterByFriendshipId(array('min' => 12)); // WHERE friendship_id > 12
     * </code>
     *
     * @param     mixed $friendshipId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByFriendshipId($friendshipId = null, $comparison = null)
    {
        if (is_array($friendshipId)) {
            $useMinMax = false;
            if (isset($friendshipId['min'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $friendshipId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($friendshipId['max'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $friendshipId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $friendshipId, $comparison);
    }

    /**
     * Filter the query on the invite_date column
     *
     * Example usage:
     * <code>
     * $query->filterByInviteDate('2011-03-14'); // WHERE invite_date = '2011-03-14'
     * $query->filterByInviteDate('now'); // WHERE invite_date = '2011-03-14'
     * $query->filterByInviteDate(array('max' => 'yesterday')); // WHERE invite_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $inviteDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByInviteDate($inviteDate = null, $comparison = null)
    {
        if (is_array($inviteDate)) {
            $useMinMax = false;
            if (isset($inviteDate['min'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_INVITE_DATE, $inviteDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inviteDate['max'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_INVITE_DATE, $inviteDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendshipTableMap::COL_INVITE_DATE, $inviteDate, $comparison);
    }

    /**
     * Filter the query on the friend1 column
     *
     * Example usage:
     * <code>
     * $query->filterByFriend1(1234); // WHERE friend1 = 1234
     * $query->filterByFriend1(array(12, 34)); // WHERE friend1 IN (12, 34)
     * $query->filterByFriend1(array('min' => 12)); // WHERE friend1 > 12
     * </code>
     *
     * @see       filterByUserRelatedByFriend1()
     *
     * @param     mixed $friend1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByFriend1($friend1 = null, $comparison = null)
    {
        if (is_array($friend1)) {
            $useMinMax = false;
            if (isset($friend1['min'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIEND1, $friend1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($friend1['max'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIEND1, $friend1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendshipTableMap::COL_FRIEND1, $friend1, $comparison);
    }

    /**
     * Filter the query on the friend2 column
     *
     * Example usage:
     * <code>
     * $query->filterByFriend2(1234); // WHERE friend2 = 1234
     * $query->filterByFriend2(array(12, 34)); // WHERE friend2 IN (12, 34)
     * $query->filterByFriend2(array('min' => 12)); // WHERE friend2 > 12
     * </code>
     *
     * @see       filterByUserRelatedByFriend2()
     *
     * @param     mixed $friend2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByFriend2($friend2 = null, $comparison = null)
    {
        if (is_array($friend2)) {
            $useMinMax = false;
            if (isset($friend2['min'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIEND2, $friend2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($friend2['max'])) {
                $this->addUsingAlias(FriendshipTableMap::COL_FRIEND2, $friend2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendshipTableMap::COL_FRIEND2, $friend2, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByFriend2($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(FriendshipTableMap::COL_FRIEND2, $user->getUserId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FriendshipTableMap::COL_FRIEND2, $user->toKeyValue('PrimaryKey', 'UserId'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByFriend2() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByFriend2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function joinUserRelatedByFriend2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByFriend2');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserRelatedByFriend2');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByFriend2 relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByFriend2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByFriend2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByFriend2', '\UserQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFriendshipQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByFriend1($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(FriendshipTableMap::COL_FRIEND1, $user->getUserId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FriendshipTableMap::COL_FRIEND1, $user->toKeyValue('PrimaryKey', 'UserId'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByFriend1() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByFriend1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function joinUserRelatedByFriend1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByFriend1');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserRelatedByFriend1');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByFriend1 relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByFriend1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByFriend1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByFriend1', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFriendship $friendship Object to remove from the list of results
     *
     * @return $this|ChildFriendshipQuery The current query, for fluid interface
     */
    public function prune($friendship = null)
    {
        if ($friendship) {
            $this->addUsingAlias(FriendshipTableMap::COL_FRIENDSHIP_ID, $friendship->getFriendshipId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the friendship table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FriendshipTableMap::clearInstancePool();
            FriendshipTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FriendshipTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FriendshipTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FriendshipTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FriendshipQuery
