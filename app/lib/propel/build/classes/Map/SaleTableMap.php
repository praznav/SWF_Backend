<?php

namespace Map;

use \Sale;
use \SaleQuery;
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
 * This class defines the structure of the 'sale' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SaleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SaleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'ShoppingWithFriends';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'sale';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Sale';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Sale';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the SALE_ID field
     */
    const COL_SALE_ID = 'sale.SALE_ID';

    /**
     * the column name for the PRICE field
     */
    const COL_PRICE = 'sale.PRICE';

    /**
     * the column name for the LOCATION field
     */
    const COL_LOCATION = 'sale.LOCATION';

    /**
     * the column name for the USER_ID field
     */
    const COL_USER_ID = 'sale.USER_ID';

    /**
     * the column name for the PRODUCT_ID field
     */
    const COL_PRODUCT_ID = 'sale.PRODUCT_ID';

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
        self::TYPE_PHPNAME       => array('SaleId', 'Price', 'Location', 'UserId', 'ProductId', ),
        self::TYPE_STUDLYPHPNAME => array('saleId', 'price', 'location', 'userId', 'productId', ),
        self::TYPE_COLNAME       => array(SaleTableMap::COL_SALE_ID, SaleTableMap::COL_PRICE, SaleTableMap::COL_LOCATION, SaleTableMap::COL_USER_ID, SaleTableMap::COL_PRODUCT_ID, ),
        self::TYPE_RAW_COLNAME   => array('COL_SALE_ID', 'COL_PRICE', 'COL_LOCATION', 'COL_USER_ID', 'COL_PRODUCT_ID', ),
        self::TYPE_FIELDNAME     => array('sale_id', 'price', 'location', 'user_id', 'product_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('SaleId' => 0, 'Price' => 1, 'Location' => 2, 'UserId' => 3, 'ProductId' => 4, ),
        self::TYPE_STUDLYPHPNAME => array('saleId' => 0, 'price' => 1, 'location' => 2, 'userId' => 3, 'productId' => 4, ),
        self::TYPE_COLNAME       => array(SaleTableMap::COL_SALE_ID => 0, SaleTableMap::COL_PRICE => 1, SaleTableMap::COL_LOCATION => 2, SaleTableMap::COL_USER_ID => 3, SaleTableMap::COL_PRODUCT_ID => 4, ),
        self::TYPE_RAW_COLNAME   => array('COL_SALE_ID' => 0, 'COL_PRICE' => 1, 'COL_LOCATION' => 2, 'COL_USER_ID' => 3, 'COL_PRODUCT_ID' => 4, ),
        self::TYPE_FIELDNAME     => array('sale_id' => 0, 'price' => 1, 'location' => 2, 'user_id' => 3, 'product_id' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('sale');
        $this->setPhpName('Sale');
        $this->setClassName('\\Sale');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('SALE_ID', 'SaleId', 'BIGINT', true, null, null);
        $this->addColumn('PRICE', 'Price', 'DECIMAL', true, 12, null);
        $this->addColumn('LOCATION', 'Location', 'VARCHAR', true, 50, null);
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
        $this->addRelation('SaleRating', '\\SaleRating', RelationMap::ONE_TO_MANY, array('sale_id' => 'sale_id', ), null, null, 'SaleRatings');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('SaleId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('SaleId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('SaleId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? SaleTableMap::CLASS_DEFAULT : SaleTableMap::OM_CLASS;
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
     * @return array           (Sale object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SaleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SaleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SaleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SaleTableMap::OM_CLASS;
            /** @var Sale $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SaleTableMap::addInstanceToPool($obj, $key);
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
            $key = SaleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SaleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Sale $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SaleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SaleTableMap::COL_SALE_ID);
            $criteria->addSelectColumn(SaleTableMap::COL_PRICE);
            $criteria->addSelectColumn(SaleTableMap::COL_LOCATION);
            $criteria->addSelectColumn(SaleTableMap::COL_USER_ID);
            $criteria->addSelectColumn(SaleTableMap::COL_PRODUCT_ID);
        } else {
            $criteria->addSelectColumn($alias . '.SALE_ID');
            $criteria->addSelectColumn($alias . '.PRICE');
            $criteria->addSelectColumn($alias . '.LOCATION');
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
        return Propel::getServiceContainer()->getDatabaseMap(SaleTableMap::DATABASE_NAME)->getTable(SaleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SaleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SaleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SaleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Sale or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Sale object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SaleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Sale) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SaleTableMap::DATABASE_NAME);
            $criteria->add(SaleTableMap::COL_SALE_ID, (array) $values, Criteria::IN);
        }

        $query = SaleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SaleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SaleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the sale table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SaleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Sale or Criteria object.
     *
     * @param mixed               $criteria Criteria or Sale object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SaleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Sale object
        }

        if ($criteria->containsKey(SaleTableMap::COL_SALE_ID) && $criteria->keyContainsValue(SaleTableMap::COL_SALE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SaleTableMap::COL_SALE_ID.')');
        }


        // Set the correct dbName
        $query = SaleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SaleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SaleTableMap::buildTableMap();
