<?php

namespace Map;

use \Friendship;
use \FriendshipQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'friendship' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FriendshipTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.FriendshipTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'ShoppingWithFriends';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'friendship';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Friendship';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Friendship';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the FRIENDSHIP_ID field
     */
    const COL_FRIENDSHIP_ID = 'friendship.FRIENDSHIP_ID';

    /**
     * the column name for the FRIENDSHIP_STATUS field
     */
    const COL_FRIENDSHIP_STATUS = 'friendship.FRIENDSHIP_STATUS';

    /**
     * the column name for the INVITE_DATE field
     */
    const COL_INVITE_DATE = 'friendship.INVITE_DATE';

    /**
     * the column name for the ACCEPTANCE_DATE field
     */
    const COL_ACCEPTANCE_DATE = 'friendship.ACCEPTANCE_DATE';

    /**
     * the column name for the FRIEND1 field
     */
    const COL_FRIEND1 = 'friendship.FRIEND1';

    /**
     * the column name for the FRIEND2 field
     */
    const COL_FRIEND2 = 'friendship.FRIEND2';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('FriendshipId', 'FriendshipStatus', 'InviteDate', 'AcceptanceDate', 'Friend1', 'Friend2', ),
        self::TYPE_STUDLYPHPNAME => array('friendshipId', 'friendshipStatus', 'inviteDate', 'acceptanceDate', 'friend1', 'friend2', ),
        self::TYPE_COLNAME       => array(FriendshipTableMap::COL_FRIENDSHIP_ID, FriendshipTableMap::COL_FRIENDSHIP_STATUS, FriendshipTableMap::COL_INVITE_DATE, FriendshipTableMap::COL_ACCEPTANCE_DATE, FriendshipTableMap::COL_FRIEND1, FriendshipTableMap::COL_FRIEND2, ),
        self::TYPE_RAW_COLNAME   => array('COL_FRIENDSHIP_ID', 'COL_FRIENDSHIP_STATUS', 'COL_INVITE_DATE', 'COL_ACCEPTANCE_DATE', 'COL_FRIEND1', 'COL_FRIEND2', ),
        self::TYPE_FIELDNAME     => array('friendship_id', 'friendship_status', 'invite_date', 'acceptance_date', 'friend1', 'friend2', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('FriendshipId' => 0, 'FriendshipStatus' => 1, 'InviteDate' => 2, 'AcceptanceDate' => 3, 'Friend1' => 4, 'Friend2' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('friendshipId' => 0, 'friendshipStatus' => 1, 'inviteDate' => 2, 'acceptanceDate' => 3, 'friend1' => 4, 'friend2' => 5, ),
        self::TYPE_COLNAME       => array(FriendshipTableMap::COL_FRIENDSHIP_ID => 0, FriendshipTableMap::COL_FRIENDSHIP_STATUS => 1, FriendshipTableMap::COL_INVITE_DATE => 2, FriendshipTableMap::COL_ACCEPTANCE_DATE => 3, FriendshipTableMap::COL_FRIEND1 => 4, FriendshipTableMap::COL_FRIEND2 => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_FRIENDSHIP_ID' => 0, 'COL_FRIENDSHIP_STATUS' => 1, 'COL_INVITE_DATE' => 2, 'COL_ACCEPTANCE_DATE' => 3, 'COL_FRIEND1' => 4, 'COL_FRIEND2' => 5, ),
        self::TYPE_FIELDNAME     => array('friendship_id' => 0, 'friendship_status' => 1, 'invite_date' => 2, 'acceptance_date' => 3, 'friend1' => 4, 'friend2' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('friendship');
        $this->setPhpName('Friendship');
        $this->setClassName('\\Friendship');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('FRIENDSHIP_ID', 'FriendshipId', 'BIGINT', true, null, null);
        $this->addColumn('FRIENDSHIP_STATUS', 'FriendshipStatus', 'TINYINT', true, null, 0);
        $this->addColumn('INVITE_DATE', 'InviteDate', 'TIMESTAMP', true, null, null);
        $this->addColumn('ACCEPTANCE_DATE', 'AcceptanceDate', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('FRIEND1', 'Friend1', 'BIGINT', 'user', 'USER_ID', false, null, null);
        $this->addForeignKey('FRIEND2', 'Friend2', 'BIGINT', 'user', 'USER_ID', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserRelatedByFriend2', '\\User', RelationMap::MANY_TO_ONE, array('friend2' => 'user_id', ), null, null);
        $this->addRelation('UserRelatedByFriend1', '\\User', RelationMap::MANY_TO_ONE, array('friend1' => 'user_id', ), null, null);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FriendshipId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FriendshipId', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('FriendshipId', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FriendshipTableMap::CLASS_DEFAULT : FriendshipTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Friendship object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FriendshipTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FriendshipTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FriendshipTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FriendshipTableMap::OM_CLASS;
            /** @var Friendship $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FriendshipTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FriendshipTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FriendshipTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Friendship $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FriendshipTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FriendshipTableMap::COL_FRIENDSHIP_ID);
            $criteria->addSelectColumn(FriendshipTableMap::COL_FRIENDSHIP_STATUS);
            $criteria->addSelectColumn(FriendshipTableMap::COL_INVITE_DATE);
            $criteria->addSelectColumn(FriendshipTableMap::COL_ACCEPTANCE_DATE);
            $criteria->addSelectColumn(FriendshipTableMap::COL_FRIEND1);
            $criteria->addSelectColumn(FriendshipTableMap::COL_FRIEND2);
        } else {
            $criteria->addSelectColumn($alias . '.FRIENDSHIP_ID');
            $criteria->addSelectColumn($alias . '.FRIENDSHIP_STATUS');
            $criteria->addSelectColumn($alias . '.INVITE_DATE');
            $criteria->addSelectColumn($alias . '.ACCEPTANCE_DATE');
            $criteria->addSelectColumn($alias . '.FRIEND1');
            $criteria->addSelectColumn($alias . '.FRIEND2');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FriendshipTableMap::DATABASE_NAME)->getTable(FriendshipTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FriendshipTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FriendshipTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FriendshipTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Friendship or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Friendship object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Friendship) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FriendshipTableMap::DATABASE_NAME);
            $criteria->add(FriendshipTableMap::COL_FRIENDSHIP_ID, (array) $values, Criteria::IN);
        }

        $query = FriendshipQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FriendshipTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FriendshipTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the friendship table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FriendshipQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Friendship or Criteria object.
     *
     * @param mixed               $criteria Criteria or Friendship object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Friendship object
        }

        if ($criteria->containsKey(FriendshipTableMap::COL_FRIENDSHIP_ID) && $criteria->keyContainsValue(FriendshipTableMap::COL_FRIENDSHIP_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FriendshipTableMap::COL_FRIENDSHIP_ID.')');
        }


        // Set the correct dbName
        $query = FriendshipQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FriendshipTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FriendshipTableMap::buildTableMap();