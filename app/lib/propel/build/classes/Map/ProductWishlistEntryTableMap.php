<?php

namespace Map;

use \ProductWishlistEntry;
use \ProductWishlistEntryQuery;
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
 * This class defines the structure of the 'product_wishlist_entry' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ProductWishlistEntryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ProductWishlistEntryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'ShoppingWithFriends';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'product_wishlist_entry';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ProductWishlistEntry';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ProductWishlistEntry';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 3;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 3;

    /**
     * the column name for the PRODUCT_WISHLIST_ENTRY_ID field
     */
    const COL_PRODUCT_WISHLIST_ENTRY_ID = 'product_wishlist_entry.PRODUCT_WISHLIST_ENTRY_ID';

    /**
     * the column name for the USER_ID field
     */
    const COL_USER_ID = 'product_wishlist_entry.USER_ID';

    /**
     * the column name for the PRODUCT_ID field
     */
    const COL_PRODUCT_ID = 'product_wishlist_entry.PRODUCT_ID';

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
        self::TYPE_PHPNAME       => array('ProductWishlistEntryId', 'UserId', 'ProductId', ),
        self::TYPE_STUDLYPHPNAME => array('productWishlistEntryId', 'userId', 'productId', ),
        self::TYPE_COLNAME       => array(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID, ProductWishlistEntryTableMap::COL_USER_ID, ProductWishlistEntryTableMap::COL_PRODUCT_ID, ),
        self::TYPE_RAW_COLNAME   => array('COL_PRODUCT_WISHLIST_ENTRY_ID', 'COL_USER_ID', 'COL_PRODUCT_ID', ),
        self::TYPE_FIELDNAME     => array('product_wishlist_entry_id', 'user_id', 'product_id', ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ProductWishlistEntryId' => 0, 'UserId' => 1, 'ProductId' => 2, ),
        self::TYPE_STUDLYPHPNAME => array('productWishlistEntryId' => 0, 'userId' => 1, 'productId' => 2, ),
        self::TYPE_COLNAME       => array(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID => 0, ProductWishlistEntryTableMap::COL_USER_ID => 1, ProductWishlistEntryTableMap::COL_PRODUCT_ID => 2, ),
        self::TYPE_RAW_COLNAME   => array('COL_PRODUCT_WISHLIST_ENTRY_ID' => 0, 'COL_USER_ID' => 1, 'COL_PRODUCT_ID' => 2, ),
        self::TYPE_FIELDNAME     => array('product_wishlist_entry_id' => 0, 'user_id' => 1, 'product_id' => 2, ),
        self::TYPE_NUM           => array(0, 1, 2, )
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
        $this->setName('product_wishlist_entry');
        $this->setPhpName('ProductWishlistEntry');
        $this->setClassName('\\ProductWishlistEntry');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PRODUCT_WISHLIST_ENTRY_ID', 'ProductWishlistEntryId', 'BIGINT', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'BIGINT', 'user', 'USER_ID', false, null, null);
        $this->addForeignKey('PRODUCT_ID', 'ProductId', 'BIGINT', 'product', 'PRODUCT_ID', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Product', '\\Product', RelationMap::MANY_TO_ONE, array('product_id' => 'product_id', ), null, null);
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'user_id', ), null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProductWishlistEntryId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProductWishlistEntryId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('ProductWishlistEntryId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? ProductWishlistEntryTableMap::CLASS_DEFAULT : ProductWishlistEntryTableMap::OM_CLASS;
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
     * @return array           (ProductWishlistEntry object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ProductWishlistEntryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ProductWishlistEntryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ProductWishlistEntryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ProductWishlistEntryTableMap::OM_CLASS;
            /** @var ProductWishlistEntry $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ProductWishlistEntryTableMap::addInstanceToPool($obj, $key);
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
            $key = ProductWishlistEntryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ProductWishlistEntryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ProductWishlistEntry $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ProductWishlistEntryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID);
            $criteria->addSelectColumn(ProductWishlistEntryTableMap::COL_USER_ID);
            $criteria->addSelectColumn(ProductWishlistEntryTableMap::COL_PRODUCT_ID);
        } else {
            $criteria->addSelectColumn($alias . '.PRODUCT_WISHLIST_ENTRY_ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.PRODUCT_ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(ProductWishlistEntryTableMap::DATABASE_NAME)->getTable(ProductWishlistEntryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ProductWishlistEntryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ProductWishlistEntryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ProductWishlistEntryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ProductWishlistEntry or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ProductWishlistEntry object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ProductWishlistEntryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ProductWishlistEntry) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ProductWishlistEntryTableMap::DATABASE_NAME);
            $criteria->add(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID, (array) $values, Criteria::IN);
        }

        $query = ProductWishlistEntryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ProductWishlistEntryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ProductWishlistEntryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the product_wishlist_entry table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ProductWishlistEntryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ProductWishlistEntry or Criteria object.
     *
     * @param mixed               $criteria Criteria or ProductWishlistEntry object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProductWishlistEntryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ProductWishlistEntry object
        }

        if ($criteria->containsKey(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID) && $criteria->keyContainsValue(ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ProductWishlistEntryTableMap::COL_PRODUCT_WISHLIST_ENTRY_ID.')');
        }


        // Set the correct dbName
        $query = ProductWishlistEntryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ProductWishlistEntryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ProductWishlistEntryTableMap::buildTableMap();
