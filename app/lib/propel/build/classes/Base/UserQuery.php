<?php

namespace Base;

use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method     ChildUserQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method     ChildUserQuery orderByLastSuccessfulLogin($order = Criteria::ASC) Order by the last_successful_login column
 * @method     ChildUserQuery orderByIncorrectLoginAttempts($order = Criteria::ASC) Order by the incorrect_login_attempts column
 * @method     ChildUserQuery orderByPrivelegeTypeId($order = Criteria::ASC) Order by the privelege_type_id column
 *
 * @method     ChildUserQuery groupByUserId() Group by the user_id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByFirstName() Group by the first_name column
 * @method     ChildUserQuery groupByLastName() Group by the last_name column
 * @method     ChildUserQuery groupByLastSuccessfulLogin() Group by the last_successful_login column
 * @method     ChildUserQuery groupByIncorrectLoginAttempts() Group by the incorrect_login_attempts column
 * @method     ChildUserQuery groupByPrivelegeTypeId() Group by the privelege_type_id column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinPrivilegeType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PrivilegeType relation
 * @method     ChildUserQuery rightJoinPrivilegeType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PrivilegeType relation
 * @method     ChildUserQuery innerJoinPrivilegeType($relationAlias = null) Adds a INNER JOIN clause to the query using the PrivilegeType relation
 *
 * @method     ChildUserQuery leftJoinFriendshipRelatedByFriend2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FriendshipRelatedByFriend2 relation
 * @method     ChildUserQuery rightJoinFriendshipRelatedByFriend2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FriendshipRelatedByFriend2 relation
 * @method     ChildUserQuery innerJoinFriendshipRelatedByFriend2($relationAlias = null) Adds a INNER JOIN clause to the query using the FriendshipRelatedByFriend2 relation
 *
 * @method     ChildUserQuery leftJoinFriendshipRelatedByFriend1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FriendshipRelatedByFriend1 relation
 * @method     ChildUserQuery rightJoinFriendshipRelatedByFriend1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FriendshipRelatedByFriend1 relation
 * @method     ChildUserQuery innerJoinFriendshipRelatedByFriend1($relationAlias = null) Adds a INNER JOIN clause to the query using the FriendshipRelatedByFriend1 relation
 *
 * @method     ChildUserQuery leftJoinInterest($relationAlias = null) Adds a LEFT JOIN clause to the query using the Interest relation
 * @method     ChildUserQuery rightJoinInterest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Interest relation
 * @method     ChildUserQuery innerJoinInterest($relationAlias = null) Adds a INNER JOIN clause to the query using the Interest relation
 *
 * @method     ChildUserQuery leftJoinSale($relationAlias = null) Adds a LEFT JOIN clause to the query using the Sale relation
 * @method     ChildUserQuery rightJoinSale($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Sale relation
 * @method     ChildUserQuery innerJoinSale($relationAlias = null) Adds a INNER JOIN clause to the query using the Sale relation
 *
 * @method     ChildUserQuery leftJoinSaleRatingRelatedByPostingUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the SaleRatingRelatedByPostingUserId relation
 * @method     ChildUserQuery rightJoinSaleRatingRelatedByPostingUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SaleRatingRelatedByPostingUserId relation
 * @method     ChildUserQuery innerJoinSaleRatingRelatedByPostingUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the SaleRatingRelatedByPostingUserId relation
 *
 * @method     ChildUserQuery leftJoinSaleRatingRelatedByRatingUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the SaleRatingRelatedByRatingUserId relation
 * @method     ChildUserQuery rightJoinSaleRatingRelatedByRatingUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SaleRatingRelatedByRatingUserId relation
 * @method     ChildUserQuery innerJoinSaleRatingRelatedByRatingUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the SaleRatingRelatedByRatingUserId relation
 *
 * @method     \PrivilegeTypeQuery|\FriendshipQuery|\InterestQuery|\SaleQuery|\SaleRatingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneByUserId(string $user_id) Return the first ChildUser filtered by the user_id column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByFirstName(string $first_name) Return the first ChildUser filtered by the first_name column
 * @method     ChildUser findOneByLastName(string $last_name) Return the first ChildUser filtered by the last_name column
 * @method     ChildUser findOneByLastSuccessfulLogin(string $last_successful_login) Return the first ChildUser filtered by the last_successful_login column
 * @method     ChildUser findOneByIncorrectLoginAttempts(int $incorrect_login_attempts) Return the first ChildUser filtered by the incorrect_login_attempts column
 * @method     ChildUser findOneByPrivelegeTypeId(int $privelege_type_id) Return the first ChildUser filtered by the privelege_type_id column
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findByUserId(string $user_id) Return ChildUser objects filtered by the user_id column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByFirstName(string $first_name) Return ChildUser objects filtered by the first_name column
 * @method     ChildUser[]|ObjectCollection findByLastName(string $last_name) Return ChildUser objects filtered by the last_name column
 * @method     ChildUser[]|ObjectCollection findByLastSuccessfulLogin(string $last_successful_login) Return ChildUser objects filtered by the last_successful_login column
 * @method     ChildUser[]|ObjectCollection findByIncorrectLoginAttempts(int $incorrect_login_attempts) Return ChildUser objects filtered by the incorrect_login_attempts column
 * @method     ChildUser[]|ObjectCollection findByPrivelegeTypeId(int $privelege_type_id) Return ChildUser objects filtered by the privelege_type_id column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ShoppingWithFriends', $modelName = '\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT USER_ID, USERNAME, PASSWORD, EMAIL, FIRST_NAME, LAST_NAME, LAST_SUCCESSFUL_LOGIN, INCORRECT_LOGIN_ATTEMPTS, PRIVELEGE_TYPE_ID FROM user WHERE USER_ID = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_USER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_USER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the last_successful_login column
     *
     * Example usage:
     * <code>
     * $query->filterByLastSuccessfulLogin('2011-03-14'); // WHERE last_successful_login = '2011-03-14'
     * $query->filterByLastSuccessfulLogin('now'); // WHERE last_successful_login = '2011-03-14'
     * $query->filterByLastSuccessfulLogin(array('max' => 'yesterday')); // WHERE last_successful_login > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastSuccessfulLogin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastSuccessfulLogin($lastSuccessfulLogin = null, $comparison = null)
    {
        if (is_array($lastSuccessfulLogin)) {
            $useMinMax = false;
            if (isset($lastSuccessfulLogin['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN, $lastSuccessfulLogin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastSuccessfulLogin['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN, $lastSuccessfulLogin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN, $lastSuccessfulLogin, $comparison);
    }

    /**
     * Filter the query on the incorrect_login_attempts column
     *
     * Example usage:
     * <code>
     * $query->filterByIncorrectLoginAttempts(1234); // WHERE incorrect_login_attempts = 1234
     * $query->filterByIncorrectLoginAttempts(array(12, 34)); // WHERE incorrect_login_attempts IN (12, 34)
     * $query->filterByIncorrectLoginAttempts(array('min' => 12)); // WHERE incorrect_login_attempts > 12
     * </code>
     *
     * @param     mixed $incorrectLoginAttempts The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIncorrectLoginAttempts($incorrectLoginAttempts = null, $comparison = null)
    {
        if (is_array($incorrectLoginAttempts)) {
            $useMinMax = false;
            if (isset($incorrectLoginAttempts['min'])) {
                $this->addUsingAlias(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS, $incorrectLoginAttempts['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incorrectLoginAttempts['max'])) {
                $this->addUsingAlias(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS, $incorrectLoginAttempts['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS, $incorrectLoginAttempts, $comparison);
    }

    /**
     * Filter the query on the privelege_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivelegeTypeId(1234); // WHERE privelege_type_id = 1234
     * $query->filterByPrivelegeTypeId(array(12, 34)); // WHERE privelege_type_id IN (12, 34)
     * $query->filterByPrivelegeTypeId(array('min' => 12)); // WHERE privelege_type_id > 12
     * </code>
     *
     * @see       filterByPrivilegeType()
     *
     * @param     mixed $privelegeTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrivelegeTypeId($privelegeTypeId = null, $comparison = null)
    {
        if (is_array($privelegeTypeId)) {
            $useMinMax = false;
            if (isset($privelegeTypeId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_PRIVELEGE_TYPE_ID, $privelegeTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($privelegeTypeId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_PRIVELEGE_TYPE_ID, $privelegeTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PRIVELEGE_TYPE_ID, $privelegeTypeId, $comparison);
    }

    /**
     * Filter the query by a related \PrivilegeType object
     *
     * @param \PrivilegeType|ObjectCollection $privilegeType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrivilegeType($privilegeType, $comparison = null)
    {
        if ($privilegeType instanceof \PrivilegeType) {
            return $this
                ->addUsingAlias(UserTableMap::COL_PRIVELEGE_TYPE_ID, $privilegeType->getPrivelegeTypeId(), $comparison);
        } elseif ($privilegeType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_PRIVELEGE_TYPE_ID, $privilegeType->toKeyValue('PrimaryKey', 'PrivelegeTypeId'), $comparison);
        } else {
            throw new PropelException('filterByPrivilegeType() only accepts arguments of type \PrivilegeType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PrivilegeType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPrivilegeType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PrivilegeType');

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
            $this->addJoinObject($join, 'PrivilegeType');
        }

        return $this;
    }

    /**
     * Use the PrivilegeType relation PrivilegeType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PrivilegeTypeQuery A secondary query class using the current class as primary query
     */
    public function usePrivilegeTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPrivilegeType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PrivilegeType', '\PrivilegeTypeQuery');
    }

    /**
     * Filter the query by a related \Friendship object
     *
     * @param \Friendship|ObjectCollection $friendship  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByFriendshipRelatedByFriend2($friendship, $comparison = null)
    {
        if ($friendship instanceof \Friendship) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $friendship->getFriend2(), $comparison);
        } elseif ($friendship instanceof ObjectCollection) {
            return $this
                ->useFriendshipRelatedByFriend2Query()
                ->filterByPrimaryKeys($friendship->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFriendshipRelatedByFriend2() only accepts arguments of type \Friendship or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FriendshipRelatedByFriend2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinFriendshipRelatedByFriend2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FriendshipRelatedByFriend2');

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
            $this->addJoinObject($join, 'FriendshipRelatedByFriend2');
        }

        return $this;
    }

    /**
     * Use the FriendshipRelatedByFriend2 relation Friendship object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \FriendshipQuery A secondary query class using the current class as primary query
     */
    public function useFriendshipRelatedByFriend2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFriendshipRelatedByFriend2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FriendshipRelatedByFriend2', '\FriendshipQuery');
    }

    /**
     * Filter the query by a related \Friendship object
     *
     * @param \Friendship|ObjectCollection $friendship  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByFriendshipRelatedByFriend1($friendship, $comparison = null)
    {
        if ($friendship instanceof \Friendship) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $friendship->getFriend1(), $comparison);
        } elseif ($friendship instanceof ObjectCollection) {
            return $this
                ->useFriendshipRelatedByFriend1Query()
                ->filterByPrimaryKeys($friendship->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFriendshipRelatedByFriend1() only accepts arguments of type \Friendship or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FriendshipRelatedByFriend1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinFriendshipRelatedByFriend1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FriendshipRelatedByFriend1');

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
            $this->addJoinObject($join, 'FriendshipRelatedByFriend1');
        }

        return $this;
    }

    /**
     * Use the FriendshipRelatedByFriend1 relation Friendship object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \FriendshipQuery A secondary query class using the current class as primary query
     */
    public function useFriendshipRelatedByFriend1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFriendshipRelatedByFriend1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FriendshipRelatedByFriend1', '\FriendshipQuery');
    }

    /**
     * Filter the query by a related \Interest object
     *
     * @param \Interest|ObjectCollection $interest  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByInterest($interest, $comparison = null)
    {
        if ($interest instanceof \Interest) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $interest->getUserId(), $comparison);
        } elseif ($interest instanceof ObjectCollection) {
            return $this
                ->useInterestQuery()
                ->filterByPrimaryKeys($interest->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInterest() only accepts arguments of type \Interest or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Interest relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinInterest($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Interest');

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
            $this->addJoinObject($join, 'Interest');
        }

        return $this;
    }

    /**
     * Use the Interest relation Interest object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \InterestQuery A secondary query class using the current class as primary query
     */
    public function useInterestQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInterest($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Interest', '\InterestQuery');
    }

    /**
     * Filter the query by a related \Sale object
     *
     * @param \Sale|ObjectCollection $sale  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySale($sale, $comparison = null)
    {
        if ($sale instanceof \Sale) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $sale->getUserId(), $comparison);
        } elseif ($sale instanceof ObjectCollection) {
            return $this
                ->useSaleQuery()
                ->filterByPrimaryKeys($sale->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \SaleRating object
     *
     * @param \SaleRating|ObjectCollection $saleRating  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySaleRatingRelatedByPostingUserId($saleRating, $comparison = null)
    {
        if ($saleRating instanceof \SaleRating) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $saleRating->getPostingUserId(), $comparison);
        } elseif ($saleRating instanceof ObjectCollection) {
            return $this
                ->useSaleRatingRelatedByPostingUserIdQuery()
                ->filterByPrimaryKeys($saleRating->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySaleRatingRelatedByPostingUserId() only accepts arguments of type \SaleRating or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SaleRatingRelatedByPostingUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinSaleRatingRelatedByPostingUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SaleRatingRelatedByPostingUserId');

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
            $this->addJoinObject($join, 'SaleRatingRelatedByPostingUserId');
        }

        return $this;
    }

    /**
     * Use the SaleRatingRelatedByPostingUserId relation SaleRating object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SaleRatingQuery A secondary query class using the current class as primary query
     */
    public function useSaleRatingRelatedByPostingUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSaleRatingRelatedByPostingUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SaleRatingRelatedByPostingUserId', '\SaleRatingQuery');
    }

    /**
     * Filter the query by a related \SaleRating object
     *
     * @param \SaleRating|ObjectCollection $saleRating  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySaleRatingRelatedByRatingUserId($saleRating, $comparison = null)
    {
        if ($saleRating instanceof \SaleRating) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USER_ID, $saleRating->getRatingUserId(), $comparison);
        } elseif ($saleRating instanceof ObjectCollection) {
            return $this
                ->useSaleRatingRelatedByRatingUserIdQuery()
                ->filterByPrimaryKeys($saleRating->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySaleRatingRelatedByRatingUserId() only accepts arguments of type \SaleRating or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SaleRatingRelatedByRatingUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinSaleRatingRelatedByRatingUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SaleRatingRelatedByRatingUserId');

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
            $this->addJoinObject($join, 'SaleRatingRelatedByRatingUserId');
        }

        return $this;
    }

    /**
     * Use the SaleRatingRelatedByRatingUserId relation SaleRating object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SaleRatingQuery A secondary query class using the current class as primary query
     */
    public function useSaleRatingRelatedByRatingUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSaleRatingRelatedByRatingUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SaleRatingRelatedByRatingUserId', '\SaleRatingQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_USER_ID, $user->getUserId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
