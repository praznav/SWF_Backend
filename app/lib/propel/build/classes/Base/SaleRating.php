<?php

namespace Base;

use \Sale as ChildSale;
use \SaleQuery as ChildSaleQuery;
use \SaleRatingQuery as ChildSaleRatingQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\SaleRatingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class SaleRating implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\SaleRatingTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the sale_rating_id field.
     * @var        string
     */
    protected $sale_rating_id;

    /**
     * The value for the rating field.
     * @var        int
     */
    protected $rating;

    /**
     * The value for the message field.
     * @var        string
     */
    protected $message;

    /**
     * The value for the sale_id field.
     * @var        string
     */
    protected $sale_id;

    /**
     * The value for the rating_user_id field.
     * @var        string
     */
    protected $rating_user_id;

    /**
     * The value for the posting_user_id field.
     * @var        string
     */
    protected $posting_user_id;

    /**
     * @var        ChildUser
     */
    protected $aUserRelatedByPostingUserId;

    /**
     * @var        ChildSale
     */
    protected $aSale;

    /**
     * @var        ChildUser
     */
    protected $aUserRelatedByRatingUserId;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Base\SaleRating object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>SaleRating</code> instance.  If
     * <code>obj</code> is an instance of <code>SaleRating</code>, delegates to
     * <code>equals(SaleRating)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|SaleRating The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [sale_rating_id] column value.
     *
     * @return string
     */
    public function getSaleRatingId()
    {
        return $this->sale_rating_id;
    }

    /**
     * Get the [rating] column value.
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Get the [message] column value.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the [sale_id] column value.
     *
     * @return string
     */
    public function getSaleId()
    {
        return $this->sale_id;
    }

    /**
     * Get the [rating_user_id] column value.
     *
     * @return string
     */
    public function getRatingUserId()
    {
        return $this->rating_user_id;
    }

    /**
     * Get the [posting_user_id] column value.
     *
     * @return string
     */
    public function getPostingUserId()
    {
        return $this->posting_user_id;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SaleRatingTableMap::translateFieldName('SaleRatingId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sale_rating_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SaleRatingTableMap::translateFieldName('Rating', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rating = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SaleRatingTableMap::translateFieldName('Message', TableMap::TYPE_PHPNAME, $indexType)];
            $this->message = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SaleRatingTableMap::translateFieldName('SaleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sale_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SaleRatingTableMap::translateFieldName('RatingUserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rating_user_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SaleRatingTableMap::translateFieldName('PostingUserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->posting_user_id = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = SaleRatingTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SaleRating'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aSale !== null && $this->sale_id !== $this->aSale->getSaleId()) {
            $this->aSale = null;
        }
        if ($this->aUserRelatedByRatingUserId !== null && $this->rating_user_id !== $this->aUserRelatedByRatingUserId->getUserId()) {
            $this->aUserRelatedByRatingUserId = null;
        }
        if ($this->aUserRelatedByPostingUserId !== null && $this->posting_user_id !== $this->aUserRelatedByPostingUserId->getUserId()) {
            $this->aUserRelatedByPostingUserId = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [sale_rating_id] column.
     *
     * @param  string $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setSaleRatingId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sale_rating_id !== $v) {
            $this->sale_rating_id = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_SALE_RATING_ID] = true;
        }

        return $this;
    } // setSaleRatingId()

    /**
     * Set the value of [rating] column.
     *
     * @param  int $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setRating($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rating !== $v) {
            $this->rating = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_RATING] = true;
        }

        return $this;
    } // setRating()

    /**
     * Set the value of [message] column.
     *
     * @param  string $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->message !== $v) {
            $this->message = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_MESSAGE] = true;
        }

        return $this;
    } // setMessage()

    /**
     * Set the value of [sale_id] column.
     *
     * @param  string $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setSaleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sale_id !== $v) {
            $this->sale_id = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_SALE_ID] = true;
        }

        if ($this->aSale !== null && $this->aSale->getSaleId() !== $v) {
            $this->aSale = null;
        }

        return $this;
    } // setSaleId()

    /**
     * Set the value of [rating_user_id] column.
     *
     * @param  string $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setRatingUserId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rating_user_id !== $v) {
            $this->rating_user_id = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_RATING_USER_ID] = true;
        }

        if ($this->aUserRelatedByRatingUserId !== null && $this->aUserRelatedByRatingUserId->getUserId() !== $v) {
            $this->aUserRelatedByRatingUserId = null;
        }

        return $this;
    } // setRatingUserId()

    /**
     * Set the value of [posting_user_id] column.
     *
     * @param  string $v new value
     * @return $this|\SaleRating The current object (for fluent API support)
     */
    public function setPostingUserId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->posting_user_id !== $v) {
            $this->posting_user_id = $v;
            $this->modifiedColumns[SaleRatingTableMap::COL_POSTING_USER_ID] = true;
        }

        if ($this->aUserRelatedByPostingUserId !== null && $this->aUserRelatedByPostingUserId->getUserId() !== $v) {
            $this->aUserRelatedByPostingUserId = null;
        }

        return $this;
    } // setPostingUserId()

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SaleRatingTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSaleRatingQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserRelatedByPostingUserId = null;
            $this->aSale = null;
            $this->aUserRelatedByRatingUserId = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see SaleRating::setDeleted()
     * @see SaleRating::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SaleRatingTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSaleRatingQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SaleRatingTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SaleRatingTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUserRelatedByPostingUserId !== null) {
                if ($this->aUserRelatedByPostingUserId->isModified() || $this->aUserRelatedByPostingUserId->isNew()) {
                    $affectedRows += $this->aUserRelatedByPostingUserId->save($con);
                }
                $this->setUserRelatedByPostingUserId($this->aUserRelatedByPostingUserId);
            }

            if ($this->aSale !== null) {
                if ($this->aSale->isModified() || $this->aSale->isNew()) {
                    $affectedRows += $this->aSale->save($con);
                }
                $this->setSale($this->aSale);
            }

            if ($this->aUserRelatedByRatingUserId !== null) {
                if ($this->aUserRelatedByRatingUserId->isModified() || $this->aUserRelatedByRatingUserId->isNew()) {
                    $affectedRows += $this->aUserRelatedByRatingUserId->save($con);
                }
                $this->setUserRelatedByRatingUserId($this->aUserRelatedByRatingUserId);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[SaleRatingTableMap::COL_SALE_RATING_ID] = true;
        if (null !== $this->sale_rating_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SaleRatingTableMap::COL_SALE_RATING_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SaleRatingTableMap::COL_SALE_RATING_ID)) {
            $modifiedColumns[':p' . $index++]  = 'SALE_RATING_ID';
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_RATING)) {
            $modifiedColumns[':p' . $index++]  = 'RATING';
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_MESSAGE)) {
            $modifiedColumns[':p' . $index++]  = 'MESSAGE';
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_SALE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'SALE_ID';
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_RATING_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'RATING_USER_ID';
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_POSTING_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'POSTING_USER_ID';
        }

        $sql = sprintf(
            'INSERT INTO sale_rating (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'SALE_RATING_ID':
                        $stmt->bindValue($identifier, $this->sale_rating_id, PDO::PARAM_INT);
                        break;
                    case 'RATING':
                        $stmt->bindValue($identifier, $this->rating, PDO::PARAM_INT);
                        break;
                    case 'MESSAGE':
                        $stmt->bindValue($identifier, $this->message, PDO::PARAM_STR);
                        break;
                    case 'SALE_ID':
                        $stmt->bindValue($identifier, $this->sale_id, PDO::PARAM_INT);
                        break;
                    case 'RATING_USER_ID':
                        $stmt->bindValue($identifier, $this->rating_user_id, PDO::PARAM_INT);
                        break;
                    case 'POSTING_USER_ID':
                        $stmt->bindValue($identifier, $this->posting_user_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setSaleRatingId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SaleRatingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getSaleRatingId();
                break;
            case 1:
                return $this->getRating();
                break;
            case 2:
                return $this->getMessage();
                break;
            case 3:
                return $this->getSaleId();
                break;
            case 4:
                return $this->getRatingUserId();
                break;
            case 5:
                return $this->getPostingUserId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['SaleRating'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SaleRating'][$this->getPrimaryKey()] = true;
        $keys = SaleRatingTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getSaleRatingId(),
            $keys[1] => $this->getRating(),
            $keys[2] => $this->getMessage(),
            $keys[3] => $this->getSaleId(),
            $keys[4] => $this->getRatingUserId(),
            $keys[5] => $this->getPostingUserId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserRelatedByPostingUserId) {
                $result['UserRelatedByPostingUserId'] = $this->aUserRelatedByPostingUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSale) {
                $result['Sale'] = $this->aSale->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByRatingUserId) {
                $result['UserRelatedByRatingUserId'] = $this->aUserRelatedByRatingUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\SaleRating
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SaleRatingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SaleRating
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setSaleRatingId($value);
                break;
            case 1:
                $this->setRating($value);
                break;
            case 2:
                $this->setMessage($value);
                break;
            case 3:
                $this->setSaleId($value);
                break;
            case 4:
                $this->setRatingUserId($value);
                break;
            case 5:
                $this->setPostingUserId($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = SaleRatingTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setSaleRatingId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setRating($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setMessage($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSaleId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRatingUserId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPostingUserId($arr[$keys[5]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return $this|\SaleRating The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SaleRatingTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SaleRatingTableMap::COL_SALE_RATING_ID)) {
            $criteria->add(SaleRatingTableMap::COL_SALE_RATING_ID, $this->sale_rating_id);
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_RATING)) {
            $criteria->add(SaleRatingTableMap::COL_RATING, $this->rating);
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_MESSAGE)) {
            $criteria->add(SaleRatingTableMap::COL_MESSAGE, $this->message);
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_SALE_ID)) {
            $criteria->add(SaleRatingTableMap::COL_SALE_ID, $this->sale_id);
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_RATING_USER_ID)) {
            $criteria->add(SaleRatingTableMap::COL_RATING_USER_ID, $this->rating_user_id);
        }
        if ($this->isColumnModified(SaleRatingTableMap::COL_POSTING_USER_ID)) {
            $criteria->add(SaleRatingTableMap::COL_POSTING_USER_ID, $this->posting_user_id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(SaleRatingTableMap::DATABASE_NAME);
        $criteria->add(SaleRatingTableMap::COL_SALE_RATING_ID, $this->sale_rating_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getSaleRatingId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getSaleRatingId();
    }

    /**
     * Generic method to set the primary key (sale_rating_id column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setSaleRatingId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getSaleRatingId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \SaleRating (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setRating($this->getRating());
        $copyObj->setMessage($this->getMessage());
        $copyObj->setSaleId($this->getSaleId());
        $copyObj->setRatingUserId($this->getRatingUserId());
        $copyObj->setPostingUserId($this->getPostingUserId());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setSaleRatingId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \SaleRating Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\SaleRating The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByPostingUserId(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setPostingUserId(NULL);
        } else {
            $this->setPostingUserId($v->getUserId());
        }

        $this->aUserRelatedByPostingUserId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addSaleRatingRelatedByPostingUserId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUserRelatedByPostingUserId(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByPostingUserId === null && (($this->posting_user_id !== "" && $this->posting_user_id !== null))) {
            $this->aUserRelatedByPostingUserId = ChildUserQuery::create()->findPk($this->posting_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByPostingUserId->addSaleRatingsRelatedByPostingUserId($this);
             */
        }

        return $this->aUserRelatedByPostingUserId;
    }

    /**
     * Declares an association between this object and a ChildSale object.
     *
     * @param  ChildSale $v
     * @return $this|\SaleRating The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSale(ChildSale $v = null)
    {
        if ($v === null) {
            $this->setSaleId(NULL);
        } else {
            $this->setSaleId($v->getSaleId());
        }

        $this->aSale = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSale object, it will not be re-added.
        if ($v !== null) {
            $v->addSaleRating($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSale object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSale The associated ChildSale object.
     * @throws PropelException
     */
    public function getSale(ConnectionInterface $con = null)
    {
        if ($this->aSale === null && (($this->sale_id !== "" && $this->sale_id !== null))) {
            $this->aSale = ChildSaleQuery::create()->findPk($this->sale_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSale->addSaleRatings($this);
             */
        }

        return $this->aSale;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\SaleRating The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByRatingUserId(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setRatingUserId(NULL);
        } else {
            $this->setRatingUserId($v->getUserId());
        }

        $this->aUserRelatedByRatingUserId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addSaleRatingRelatedByRatingUserId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUserRelatedByRatingUserId(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByRatingUserId === null && (($this->rating_user_id !== "" && $this->rating_user_id !== null))) {
            $this->aUserRelatedByRatingUserId = ChildUserQuery::create()->findPk($this->rating_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByRatingUserId->addSaleRatingsRelatedByRatingUserId($this);
             */
        }

        return $this->aUserRelatedByRatingUserId;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUserRelatedByPostingUserId) {
            $this->aUserRelatedByPostingUserId->removeSaleRatingRelatedByPostingUserId($this);
        }
        if (null !== $this->aSale) {
            $this->aSale->removeSaleRating($this);
        }
        if (null !== $this->aUserRelatedByRatingUserId) {
            $this->aUserRelatedByRatingUserId->removeSaleRatingRelatedByRatingUserId($this);
        }
        $this->sale_rating_id = null;
        $this->rating = null;
        $this->message = null;
        $this->sale_id = null;
        $this->rating_user_id = null;
        $this->posting_user_id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aUserRelatedByPostingUserId = null;
        $this->aSale = null;
        $this->aUserRelatedByRatingUserId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SaleRatingTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
