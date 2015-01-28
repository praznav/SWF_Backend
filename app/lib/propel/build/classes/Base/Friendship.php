<?php

namespace Base;

use \FriendshipQuery as ChildFriendshipQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\FriendshipTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

abstract class Friendship implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\FriendshipTableMap';


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
     * The value for the friendship_id field.
     * @var        string
     */
    protected $friendship_id;

    /**
     * The value for the friendship_status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $friendship_status;

    /**
     * The value for the invite_date field.
     * @var        \DateTime
     */
    protected $invite_date;

    /**
     * The value for the acceptance_date field.
     * @var        \DateTime
     */
    protected $acceptance_date;

    /**
     * The value for the friend1 field.
     * @var        string
     */
    protected $friend1;

    /**
     * The value for the friend2 field.
     * @var        string
     */
    protected $friend2;

    /**
     * @var        ChildUser
     */
    protected $aUserRelatedByFriend2;

    /**
     * @var        ChildUser
     */
    protected $aUserRelatedByFriend1;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->friendship_status = 0;
    }

    /**
     * Initializes internal state of Base\Friendship object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Friendship</code> instance.  If
     * <code>obj</code> is an instance of <code>Friendship</code>, delegates to
     * <code>equals(Friendship)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Friendship The current object, for fluid interface
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
     * Get the [friendship_id] column value.
     *
     * @return string
     */
    public function getFriendshipId()
    {
        return $this->friendship_id;
    }

    /**
     * Get the [friendship_status] column value.
     *
     * @return int
     */
    public function getFriendshipStatus()
    {
        return $this->friendship_status;
    }

    /**
     * Get the [optionally formatted] temporal [invite_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getInviteDate($format = NULL)
    {
        if ($format === null) {
            return $this->invite_date;
        } else {
            return $this->invite_date instanceof \DateTime ? $this->invite_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [acceptance_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getAcceptanceDate($format = NULL)
    {
        if ($format === null) {
            return $this->acceptance_date;
        } else {
            return $this->acceptance_date instanceof \DateTime ? $this->acceptance_date->format($format) : null;
        }
    }

    /**
     * Get the [friend1] column value.
     *
     * @return string
     */
    public function getFriend1()
    {
        return $this->friend1;
    }

    /**
     * Get the [friend2] column value.
     *
     * @return string
     */
    public function getFriend2()
    {
        return $this->friend2;
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
            if ($this->friendship_status !== 0) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FriendshipTableMap::translateFieldName('FriendshipId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->friendship_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FriendshipTableMap::translateFieldName('FriendshipStatus', TableMap::TYPE_PHPNAME, $indexType)];
            $this->friendship_status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FriendshipTableMap::translateFieldName('InviteDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->invite_date = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FriendshipTableMap::translateFieldName('AcceptanceDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->acceptance_date = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FriendshipTableMap::translateFieldName('Friend1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->friend1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FriendshipTableMap::translateFieldName('Friend2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->friend2 = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = FriendshipTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Friendship'), 0, $e);
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
        if ($this->aUserRelatedByFriend1 !== null && $this->friend1 !== $this->aUserRelatedByFriend1->getUserId()) {
            $this->aUserRelatedByFriend1 = null;
        }
        if ($this->aUserRelatedByFriend2 !== null && $this->friend2 !== $this->aUserRelatedByFriend2->getUserId()) {
            $this->aUserRelatedByFriend2 = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [friendship_id] column.
     *
     * @param  string $v new value
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setFriendshipId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->friendship_id !== $v) {
            $this->friendship_id = $v;
            $this->modifiedColumns[FriendshipTableMap::COL_FRIENDSHIP_ID] = true;
        }

        return $this;
    } // setFriendshipId()

    /**
     * Set the value of [friendship_status] column.
     *
     * @param  int $v new value
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setFriendshipStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->friendship_status !== $v) {
            $this->friendship_status = $v;
            $this->modifiedColumns[FriendshipTableMap::COL_FRIENDSHIP_STATUS] = true;
        }

        return $this;
    } // setFriendshipStatus()

    /**
     * Sets the value of [invite_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setInviteDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->invite_date !== null || $dt !== null) {
            if ($dt !== $this->invite_date) {
                $this->invite_date = $dt;
                $this->modifiedColumns[FriendshipTableMap::COL_INVITE_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setInviteDate()

    /**
     * Sets the value of [acceptance_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setAcceptanceDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->acceptance_date !== null || $dt !== null) {
            if ($dt !== $this->acceptance_date) {
                $this->acceptance_date = $dt;
                $this->modifiedColumns[FriendshipTableMap::COL_ACCEPTANCE_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setAcceptanceDate()

    /**
     * Set the value of [friend1] column.
     *
     * @param  string $v new value
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setFriend1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->friend1 !== $v) {
            $this->friend1 = $v;
            $this->modifiedColumns[FriendshipTableMap::COL_FRIEND1] = true;
        }

        if ($this->aUserRelatedByFriend1 !== null && $this->aUserRelatedByFriend1->getUserId() !== $v) {
            $this->aUserRelatedByFriend1 = null;
        }

        return $this;
    } // setFriend1()

    /**
     * Set the value of [friend2] column.
     *
     * @param  string $v new value
     * @return $this|\Friendship The current object (for fluent API support)
     */
    public function setFriend2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->friend2 !== $v) {
            $this->friend2 = $v;
            $this->modifiedColumns[FriendshipTableMap::COL_FRIEND2] = true;
        }

        if ($this->aUserRelatedByFriend2 !== null && $this->aUserRelatedByFriend2->getUserId() !== $v) {
            $this->aUserRelatedByFriend2 = null;
        }

        return $this;
    } // setFriend2()

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
            $con = Propel::getServiceContainer()->getReadConnection(FriendshipTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFriendshipQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserRelatedByFriend2 = null;
            $this->aUserRelatedByFriend1 = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Friendship::setDeleted()
     * @see Friendship::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFriendshipQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(FriendshipTableMap::DATABASE_NAME);
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
                FriendshipTableMap::addInstanceToPool($this);
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

            if ($this->aUserRelatedByFriend2 !== null) {
                if ($this->aUserRelatedByFriend2->isModified() || $this->aUserRelatedByFriend2->isNew()) {
                    $affectedRows += $this->aUserRelatedByFriend2->save($con);
                }
                $this->setUserRelatedByFriend2($this->aUserRelatedByFriend2);
            }

            if ($this->aUserRelatedByFriend1 !== null) {
                if ($this->aUserRelatedByFriend1->isModified() || $this->aUserRelatedByFriend1->isNew()) {
                    $affectedRows += $this->aUserRelatedByFriend1->save($con);
                }
                $this->setUserRelatedByFriend1($this->aUserRelatedByFriend1);
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

        $this->modifiedColumns[FriendshipTableMap::COL_FRIENDSHIP_ID] = true;
        if (null !== $this->friendship_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FriendshipTableMap::COL_FRIENDSHIP_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIENDSHIP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'FRIENDSHIP_ID';
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIENDSHIP_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'FRIENDSHIP_STATUS';
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_INVITE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'INVITE_DATE';
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_ACCEPTANCE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'ACCEPTANCE_DATE';
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIEND1)) {
            $modifiedColumns[':p' . $index++]  = 'FRIEND1';
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIEND2)) {
            $modifiedColumns[':p' . $index++]  = 'FRIEND2';
        }

        $sql = sprintf(
            'INSERT INTO friendship (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'FRIENDSHIP_ID':
                        $stmt->bindValue($identifier, $this->friendship_id, PDO::PARAM_INT);
                        break;
                    case 'FRIENDSHIP_STATUS':
                        $stmt->bindValue($identifier, $this->friendship_status, PDO::PARAM_INT);
                        break;
                    case 'INVITE_DATE':
                        $stmt->bindValue($identifier, $this->invite_date ? $this->invite_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'ACCEPTANCE_DATE':
                        $stmt->bindValue($identifier, $this->acceptance_date ? $this->acceptance_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'FRIEND1':
                        $stmt->bindValue($identifier, $this->friend1, PDO::PARAM_INT);
                        break;
                    case 'FRIEND2':
                        $stmt->bindValue($identifier, $this->friend2, PDO::PARAM_INT);
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
        $this->setFriendshipId($pk);

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
        $pos = FriendshipTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFriendshipId();
                break;
            case 1:
                return $this->getFriendshipStatus();
                break;
            case 2:
                return $this->getInviteDate();
                break;
            case 3:
                return $this->getAcceptanceDate();
                break;
            case 4:
                return $this->getFriend1();
                break;
            case 5:
                return $this->getFriend2();
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
        if (isset($alreadyDumpedObjects['Friendship'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Friendship'][$this->getPrimaryKey()] = true;
        $keys = FriendshipTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getFriendshipId(),
            $keys[1] => $this->getFriendshipStatus(),
            $keys[2] => $this->getInviteDate(),
            $keys[3] => $this->getAcceptanceDate(),
            $keys[4] => $this->getFriend1(),
            $keys[5] => $this->getFriend2(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserRelatedByFriend2) {
                $result['UserRelatedByFriend2'] = $this->aUserRelatedByFriend2->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByFriend1) {
                $result['UserRelatedByFriend1'] = $this->aUserRelatedByFriend1->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Friendship
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FriendshipTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Friendship
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setFriendshipId($value);
                break;
            case 1:
                $this->setFriendshipStatus($value);
                break;
            case 2:
                $this->setInviteDate($value);
                break;
            case 3:
                $this->setAcceptanceDate($value);
                break;
            case 4:
                $this->setFriend1($value);
                break;
            case 5:
                $this->setFriend2($value);
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
        $keys = FriendshipTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setFriendshipId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFriendshipStatus($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setInviteDate($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAcceptanceDate($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFriend1($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setFriend2($arr[$keys[5]]);
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
     * @return $this|\Friendship The current object, for fluid interface
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
        $criteria = new Criteria(FriendshipTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FriendshipTableMap::COL_FRIENDSHIP_ID)) {
            $criteria->add(FriendshipTableMap::COL_FRIENDSHIP_ID, $this->friendship_id);
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIENDSHIP_STATUS)) {
            $criteria->add(FriendshipTableMap::COL_FRIENDSHIP_STATUS, $this->friendship_status);
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_INVITE_DATE)) {
            $criteria->add(FriendshipTableMap::COL_INVITE_DATE, $this->invite_date);
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_ACCEPTANCE_DATE)) {
            $criteria->add(FriendshipTableMap::COL_ACCEPTANCE_DATE, $this->acceptance_date);
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIEND1)) {
            $criteria->add(FriendshipTableMap::COL_FRIEND1, $this->friend1);
        }
        if ($this->isColumnModified(FriendshipTableMap::COL_FRIEND2)) {
            $criteria->add(FriendshipTableMap::COL_FRIEND2, $this->friend2);
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
        $criteria = new Criteria(FriendshipTableMap::DATABASE_NAME);
        $criteria->add(FriendshipTableMap::COL_FRIENDSHIP_ID, $this->friendship_id);

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
        $validPk = null !== $this->getFriendshipId();

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
        return $this->getFriendshipId();
    }

    /**
     * Generic method to set the primary key (friendship_id column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setFriendshipId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getFriendshipId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Friendship (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFriendshipStatus($this->getFriendshipStatus());
        $copyObj->setInviteDate($this->getInviteDate());
        $copyObj->setAcceptanceDate($this->getAcceptanceDate());
        $copyObj->setFriend1($this->getFriend1());
        $copyObj->setFriend2($this->getFriend2());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setFriendshipId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Friendship Clone of current object.
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
     * @return $this|\Friendship The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByFriend2(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setFriend2(NULL);
        } else {
            $this->setFriend2($v->getUserId());
        }

        $this->aUserRelatedByFriend2 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addFriendshipRelatedByFriend2($this);
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
    public function getUserRelatedByFriend2(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByFriend2 === null && (($this->friend2 !== "" && $this->friend2 !== null))) {
            $this->aUserRelatedByFriend2 = ChildUserQuery::create()->findPk($this->friend2, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByFriend2->addFriendshipsRelatedByFriend2($this);
             */
        }

        return $this->aUserRelatedByFriend2;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Friendship The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByFriend1(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setFriend1(NULL);
        } else {
            $this->setFriend1($v->getUserId());
        }

        $this->aUserRelatedByFriend1 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addFriendshipRelatedByFriend1($this);
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
    public function getUserRelatedByFriend1(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByFriend1 === null && (($this->friend1 !== "" && $this->friend1 !== null))) {
            $this->aUserRelatedByFriend1 = ChildUserQuery::create()->findPk($this->friend1, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByFriend1->addFriendshipsRelatedByFriend1($this);
             */
        }

        return $this->aUserRelatedByFriend1;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUserRelatedByFriend2) {
            $this->aUserRelatedByFriend2->removeFriendshipRelatedByFriend2($this);
        }
        if (null !== $this->aUserRelatedByFriend1) {
            $this->aUserRelatedByFriend1->removeFriendshipRelatedByFriend1($this);
        }
        $this->friendship_id = null;
        $this->friendship_status = null;
        $this->invite_date = null;
        $this->acceptance_date = null;
        $this->friend1 = null;
        $this->friend2 = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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

        $this->aUserRelatedByFriend2 = null;
        $this->aUserRelatedByFriend1 = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FriendshipTableMap::DEFAULT_STRING_FORMAT);
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
