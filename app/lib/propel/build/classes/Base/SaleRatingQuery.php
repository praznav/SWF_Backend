<?php

namespace Base;

use \SaleRating as ChildSaleRating;
use \SaleRatingQuery as ChildSaleRatingQuery;
use \Exception;
use \PDO;
use Map\SaleRatingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sale_rating' table.
 *
 *
 *
 * @method     ChildSaleRatingQuery orderBySaleRatingId($order = Criteria::ASC) Order by the sale_rating_id column
 * @method     ChildSaleRatingQuery orderByRating($order = Criteria::ASC) Order by the rating column
 * @method     ChildSaleRatingQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildSaleRatingQuery orderBySaleId($order = Criteria::ASC) Order by the sale_id column
 * @method     ChildSaleRatingQuery orderByRatingUserId($order = Criteria::ASC) Order by the rating_user_id column
 * @method     ChildSaleRatingQuery orderByPostingUserId($order = Criteria::ASC) Order by the posting_user_id column
 *
 * @method     ChildSaleRatingQuery groupBySaleRatingId() Group by the sale_rating_id column
 * @method     ChildSaleRatingQuery groupByRating() Group by the rating column
 * @method     ChildSaleRatingQuery groupByMessage() Group by the message column
 * @method     ChildSaleRatingQuery groupBySaleId() Group by the sale_id column
 * @method     ChildSaleRatingQuery groupByRatingUserId() Group by the rating_user_id column
 * @method     ChildSaleRatingQuery groupByPostingUserId() Group by the posting_user_id column
 *
 * @method     ChildSaleRatingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSaleRatingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSaleRatingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSaleRatingQuery leftJoinUserRelatedByPostingUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByPostingUserId relation
 * @method     ChildSaleRatingQuery rightJoinUserRelatedByPostingUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByPostingUserId relation
 * @method     ChildSaleRatingQuery innerJoinUserRelatedByPostingUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByPostingUserId relation
 *
 * @method     ChildSaleRatingQuery leftJoinSale($relationAlias = null) Adds a LEFT JOIN clause to the query using the Sale relation
 * @method     ChildSaleRatingQuery rightJoinSale($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Sale relation
 * @method     ChildSaleRatingQuery innerJoinSale($relationAlias = null) Adds a INNER JOIN clause to the query using the Sale relation
 *
 * @method     ChildSaleRatingQuery leftJoinUserRelatedByRatingUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByRatingUserId relation
 * @method     ChildSaleRatingQuery rightJoinUserRelatedByRatingUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByRatingUserId relation
 * @method     ChildSaleRatingQuery innerJoinUserRelatedByRatingUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByRatingUserId relation
 *
 * @method     \UserQuery|\SaleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSaleRating findOne(ConnectionInterface $con = null) Return the first ChildSaleRating matching the query
 * @method     ChildSaleRating findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSaleRating matching the query, or a new ChildSaleRating object populated from the query conditions when no match is found
 *
 * @method     ChildSaleRating findOneBySaleRatingId(string $sale_rating_id) Return the first ChildSaleRating filtered by the sale_rating_id column
 * @method     ChildSaleRating findOneByRating(int $rating) Return the first ChildSaleRating filtered by the rating column
 * @method     ChildSaleRating findOneByMessage(string $message) Return the first ChildSaleRating filtered by the message column
 * @method     ChildSaleRating findOneBySaleId(string $sale_id) Return the first ChildSaleRating filtered by the sale_id column
 * @method     ChildSaleRating findOneByRatingUserId(string $rating_user_id) Return the first ChildSaleRating filtered by the rating_user_id column
 * @method     ChildSaleRating findOneByPostingUserId(string $posting_user_id) Return the first ChildSaleRating filtered by the posting_user_id column
 *
 * @method     ChildSaleRating[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSaleRating objects based on current ModelCriteria
 * @method     ChildSaleRating[]|ObjectCollection findBySaleRatingId(string $sale_rating_id) Return ChildSaleRating objects filtered by the sale_rating_id column
 * @method     ChildSaleRating[]|ObjectCollection findByRating(int $rating) Return ChildSaleRating objects filtered by the rating column
 * @method     ChildSaleRating[]|ObjectCollection findByMessage(string $message) Return ChildSaleRating objects filtered by the message column
 * @method     ChildSaleRating[]|ObjectCollection findBySaleId(string $sale_id) Return ChildSaleRating objects filtered by the sale_id column
 * @method     ChildSaleRating[]|ObjectCollection findByRatingUserId(string $rating_user_id) Return ChildSaleRating objects filtered by the rating_user_id column
 * @method     ChildSaleRating[]|ObjectCollection findByPostingUserId(string $posting_user_id) Return ChildSaleRating objects filtered by the posting_user_id column
 * @method     ChildSaleRating[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SaleRatingQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\SaleRatingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ShoppingWithFriends', $modelName = '\\SaleRating', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSaleRatingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSaleRatingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSaleRatingQuery) {
            return $criteria;
        }
        $query = new ChildSaleRatingQuery();
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
     * @return ChildSaleRating|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SaleRatingTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SaleRatingTableMap::DATABASE_NAME);
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
     * @return ChildSaleRating A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT SALE_RATING_ID, RATING, MESSAGE, SALE_ID, RATING_USER_ID, POSTING_USER_ID FROM sale_rating WHERE SALE_RATING_ID = :p0';
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
            /** @var ChildSaleRating $obj */
            $obj = new ChildSaleRating();
            $obj->hydrate($row);
            SaleRatingTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSaleRating|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the sale_rating_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySaleRatingId(1234); // WHERE sale_rating_id = 1234
     * $query->filterBySaleRatingId(array(12, 34)); // WHERE sale_rating_id IN (12, 34)
     * $query->filterBySaleRatingId(array('min' => 12)); // WHERE sale_rating_id > 12
     * </code>
     *
     * @param     mixed $saleRatingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterBySaleRatingId($saleRatingId = null, $comparison = null)
    {
        if (is_array($saleRatingId)) {
            $useMinMax = false;
            if (isset($saleRatingId['min'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $saleRatingId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($saleRatingId['max'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $saleRatingId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $saleRatingId, $comparison);
    }

    /**
     * Filter the query on the rating column
     *
     * Example usage:
     * <code>
     * $query->filterByRating(1234); // WHERE rating = 1234
     * $query->filterByRating(array(12, 34)); // WHERE rating IN (12, 34)
     * $query->filterByRating(array('min' => 12)); // WHERE rating > 12
     * </code>
     *
     * @param     mixed $rating The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByRating($rating = null, $comparison = null)
    {
        if (is_array($rating)) {
            $useMinMax = false;
            if (isset($rating['min'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_RATING, $rating['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rating['max'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_RATING, $rating['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_RATING, $rating, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the sale_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySaleId(1234); // WHERE sale_id = 1234
     * $query->filterBySaleId(array(12, 34)); // WHERE sale_id IN (12, 34)
     * $query->filterBySaleId(array('min' => 12)); // WHERE sale_id > 12
     * </code>
     *
     * @see       filterBySale()
     *
     * @param     mixed $saleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterBySaleId($saleId = null, $comparison = null)
    {
        if (is_array($saleId)) {
            $useMinMax = false;
            if (isset($saleId['min'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_SALE_ID, $saleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($saleId['max'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_SALE_ID, $saleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_SALE_ID, $saleId, $comparison);
    }

    /**
     * Filter the query on the rating_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRatingUserId(1234); // WHERE rating_user_id = 1234
     * $query->filterByRatingUserId(array(12, 34)); // WHERE rating_user_id IN (12, 34)
     * $query->filterByRatingUserId(array('min' => 12)); // WHERE rating_user_id > 12
     * </code>
     *
     * @see       filterByUserRelatedByRatingUserId()
     *
     * @param     mixed $ratingUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByRatingUserId($ratingUserId = null, $comparison = null)
    {
        if (is_array($ratingUserId)) {
            $useMinMax = false;
            if (isset($ratingUserId['min'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_RATING_USER_ID, $ratingUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ratingUserId['max'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_RATING_USER_ID, $ratingUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_RATING_USER_ID, $ratingUserId, $comparison);
    }

    /**
     * Filter the query on the posting_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPostingUserId(1234); // WHERE posting_user_id = 1234
     * $query->filterByPostingUserId(array(12, 34)); // WHERE posting_user_id IN (12, 34)
     * $query->filterByPostingUserId(array('min' => 12)); // WHERE posting_user_id > 12
     * </code>
     *
     * @see       filterByUserRelatedByPostingUserId()
     *
     * @param     mixed $postingUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByPostingUserId($postingUserId = null, $comparison = null)
    {
        if (is_array($postingUserId)) {
            $useMinMax = false;
            if (isset($postingUserId['min'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_POSTING_USER_ID, $postingUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($postingUserId['max'])) {
                $this->addUsingAlias(SaleRatingTableMap::COL_POSTING_USER_ID, $postingUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SaleRatingTableMap::COL_POSTING_USER_ID, $postingUserId, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByPostingUserId($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_POSTING_USER_ID, $user->getUserId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_POSTING_USER_ID, $user->toKeyValue('PrimaryKey', 'UserId'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByPostingUserId() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByPostingUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function joinUserRelatedByPostingUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByPostingUserId');

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
            $this->addJoinObject($join, 'UserRelatedByPostingUserId');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByPostingUserId relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByPostingUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByPostingUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByPostingUserId', '\UserQuery');
    }

    /**
     * Filter the query by a related \Sale object
     *
     * @param \Sale|ObjectCollection $sale The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterBySale($sale, $comparison = null)
    {
        if ($sale instanceof \Sale) {
            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_SALE_ID, $sale->getSaleId(), $comparison);
        } elseif ($sale instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_SALE_ID, $sale->toKeyValue('PrimaryKey', 'SaleId'), $comparison);
        } else {
            throw new PropelException('filterBySale() only accepts arguments of type \Sale or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Sale relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function joinSale($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Sale');

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
            $this->addJoinObject($join, 'Sale');
        }

        return $this;
    }

    /**
     * Use the Sale relation Sale object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SaleQuery A secondary query class using the current class as primary query
     */
    public function useSaleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSale($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Sale', '\SaleQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSaleRatingQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByRatingUserId($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_RATING_USER_ID, $user->getUserId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SaleRatingTableMap::COL_RATING_USER_ID, $user->toKeyValue('PrimaryKey', 'UserId'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByRatingUserId() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByRatingUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function joinUserRelatedByRatingUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByRatingUserId');

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
            $this->addJoinObject($join, 'UserRelatedByRatingUserId');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByRatingUserId relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByRatingUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByRatingUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByRatingUserId', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSaleRating $saleRating Object to remove from the list of results
     *
     * @return $this|ChildSaleRatingQuery The current query, for fluid interface
     */
    public function prune($saleRating = null)
    {
        if ($saleRating) {
            $this->addUsingAlias(SaleRatingTableMap::COL_SALE_RATING_ID, $saleRating->getSaleRatingId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sale_rating table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SaleRatingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SaleRatingTableMap::clearInstancePool();
            SaleRatingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SaleRatingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SaleRatingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SaleRatingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SaleRatingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SaleRatingQuery
