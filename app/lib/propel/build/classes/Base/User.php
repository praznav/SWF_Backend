<?php

namespace Base;

use \Friendship as ChildFriendship;
use \FriendshipQuery as ChildFriendshipQuery;
use \PrivilegeType as ChildPrivilegeType;
use \PrivilegeTypeQuery as ChildPrivilegeTypeQuery;
use \ProductWishlistEntry as ChildProductWishlistEntry;
use \ProductWishlistEntryQuery as ChildProductWishlistEntryQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the user_id field.
     * @var        string
     */
    protected $user_id;

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the last_successful_login field.
     * @var        \DateTime
     */
    protected $last_successful_login;

    /**
     * The value for the incorrect_login_attempts field.
     * @var        int
     */
    protected $incorrect_login_attempts;

    /**
     * The value for the privelege_type_id field.
     * @var        int
     */
    protected $privelege_type_id;

    /**
     * @var        ChildPrivilegeType
     */
    protected $aPrivilegeType;

    /**
     * @var        ObjectCollection|ChildFriendship[] Collection to store aggregation of ChildFriendship objects.
     */
    protected $collFriendshipsRelatedByFriend2;
    protected $collFriendshipsRelatedByFriend2Partial;

    /**
     * @var        ObjectCollection|ChildFriendship[] Collection to store aggregation of ChildFriendship objects.
     */
    protected $collFriendshipsRelatedByFriend1;
    protected $collFriendshipsRelatedByFriend1Partial;

    /**
     * @var        ObjectCollection|ChildProductWishlistEntry[] Collection to store aggregation of ChildProductWishlistEntry objects.
     */
    protected $collProductWishlistEntries;
    protected $collProductWishlistEntriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFriendship[]
     */
    protected $friendshipsRelatedByFriend2ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFriendship[]
     */
    protected $friendshipsRelatedByFriend1ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProductWishlistEntry[]
     */
    protected $productWishlistEntriesScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [user_id] column value.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get the [optionally formatted] temporal [last_successful_login] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastSuccessfulLogin($format = NULL)
    {
        if ($format === null) {
            return $this->last_successful_login;
        } else {
            return $this->last_successful_login instanceof \DateTime ? $this->last_successful_login->format($format) : null;
        }
    }

    /**
     * Get the [incorrect_login_attempts] column value.
     *
     * @return int
     */
    public function getIncorrectLoginAttempts()
    {
        return $this->incorrect_login_attempts;
    }

    /**
     * Get the [privelege_type_id] column value.
     *
     * @return int
     */
    public function getPrivelegeTypeId()
    {
        return $this->privelege_type_id;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('LastSuccessfulLogin', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_successful_login = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('IncorrectLoginAttempts', TableMap::TYPE_PHPNAME, $indexType)];
            $this->incorrect_login_attempts = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('PrivelegeTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->privelege_type_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
        if ($this->aPrivilegeType !== null && $this->privelege_type_id !== $this->aPrivilegeType->getPrivelegeTypeId()) {
            $this->aPrivilegeType = null;
        }
    } // ensureConsistency

    /**
     * Set the value of [user_id] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_ID] = true;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[UserTableMap::COL_FIRST_NAME] = true;
        }

        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[UserTableMap::COL_LAST_NAME] = true;
        }

        return $this;
    } // setLastName()

    /**
     * Sets the value of [last_successful_login] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLastSuccessfulLogin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->last_successful_login !== null || $dt !== null) {
            if ($dt !== $this->last_successful_login) {
                $this->last_successful_login = $dt;
                $this->modifiedColumns[UserTableMap::COL_LAST_SUCCESSFUL_LOGIN] = true;
            }
        } // if either are not null

        return $this;
    } // setLastSuccessfulLogin()

    /**
     * Set the value of [incorrect_login_attempts] column.
     *
     * @param  int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setIncorrectLoginAttempts($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->incorrect_login_attempts !== $v) {
            $this->incorrect_login_attempts = $v;
            $this->modifiedColumns[UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS] = true;
        }

        return $this;
    } // setIncorrectLoginAttempts()

    /**
     * Set the value of [privelege_type_id] column.
     *
     * @param  int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPrivelegeTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->privelege_type_id !== $v) {
            $this->privelege_type_id = $v;
            $this->modifiedColumns[UserTableMap::COL_PRIVELEGE_TYPE_ID] = true;
        }

        if ($this->aPrivilegeType !== null && $this->aPrivilegeType->getPrivelegeTypeId() !== $v) {
            $this->aPrivilegeType = null;
        }

        return $this;
    } // setPrivelegeTypeId()

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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPrivilegeType = null;
            $this->collFriendshipsRelatedByFriend2 = null;

            $this->collFriendshipsRelatedByFriend1 = null;

            $this->collProductWishlistEntries = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->aPrivilegeType !== null) {
                if ($this->aPrivilegeType->isModified() || $this->aPrivilegeType->isNew()) {
                    $affectedRows += $this->aPrivilegeType->save($con);
                }
                $this->setPrivilegeType($this->aPrivilegeType);
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

            if ($this->friendshipsRelatedByFriend2ScheduledForDeletion !== null) {
                if (!$this->friendshipsRelatedByFriend2ScheduledForDeletion->isEmpty()) {
                    foreach ($this->friendshipsRelatedByFriend2ScheduledForDeletion as $friendshipRelatedByFriend2) {
                        // need to save related object because we set the relation to null
                        $friendshipRelatedByFriend2->save($con);
                    }
                    $this->friendshipsRelatedByFriend2ScheduledForDeletion = null;
                }
            }

            if ($this->collFriendshipsRelatedByFriend2 !== null) {
                foreach ($this->collFriendshipsRelatedByFriend2 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->friendshipsRelatedByFriend1ScheduledForDeletion !== null) {
                if (!$this->friendshipsRelatedByFriend1ScheduledForDeletion->isEmpty()) {
                    foreach ($this->friendshipsRelatedByFriend1ScheduledForDeletion as $friendshipRelatedByFriend1) {
                        // need to save related object because we set the relation to null
                        $friendshipRelatedByFriend1->save($con);
                    }
                    $this->friendshipsRelatedByFriend1ScheduledForDeletion = null;
                }
            }

            if ($this->collFriendshipsRelatedByFriend1 !== null) {
                foreach ($this->collFriendshipsRelatedByFriend1 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->productWishlistEntriesScheduledForDeletion !== null) {
                if (!$this->productWishlistEntriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->productWishlistEntriesScheduledForDeletion as $productWishlistEntry) {
                        // need to save related object because we set the relation to null
                        $productWishlistEntry->save($con);
                    }
                    $this->productWishlistEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collProductWishlistEntries !== null) {
                foreach ($this->collProductWishlistEntries as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[UserTableMap::COL_USER_ID] = true;
        if (null !== $this->user_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_USER_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'USER_ID';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'USERNAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'PASSWORD';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL';
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'FIRST_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'LAST_NAME';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'LAST_SUCCESSFUL_LOGIN';
        }
        if ($this->isColumnModified(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS)) {
            $modifiedColumns[':p' . $index++]  = 'INCORRECT_LOGIN_ATTEMPTS';
        }
        if ($this->isColumnModified(UserTableMap::COL_PRIVELEGE_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'PRIVELEGE_TYPE_ID';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'USER_ID':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'USERNAME':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'PASSWORD':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'EMAIL':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'FIRST_NAME':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case 'LAST_NAME':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case 'LAST_SUCCESSFUL_LOGIN':
                        $stmt->bindValue($identifier, $this->last_successful_login ? $this->last_successful_login->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'INCORRECT_LOGIN_ATTEMPTS':
                        $stmt->bindValue($identifier, $this->incorrect_login_attempts, PDO::PARAM_INT);
                        break;
                    case 'PRIVELEGE_TYPE_ID':
                        $stmt->bindValue($identifier, $this->privelege_type_id, PDO::PARAM_INT);
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
        $this->setUserId($pk);

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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUserId();
                break;
            case 1:
                return $this->getUsername();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getFirstName();
                break;
            case 5:
                return $this->getLastName();
                break;
            case 6:
                return $this->getLastSuccessfulLogin();
                break;
            case 7:
                return $this->getIncorrectLoginAttempts();
                break;
            case 8:
                return $this->getPrivelegeTypeId();
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
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUserId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getFirstName(),
            $keys[5] => $this->getLastName(),
            $keys[6] => $this->getLastSuccessfulLogin(),
            $keys[7] => $this->getIncorrectLoginAttempts(),
            $keys[8] => $this->getPrivelegeTypeId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPrivilegeType) {
                $result['PrivilegeType'] = $this->aPrivilegeType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFriendshipsRelatedByFriend2) {
                $result['FriendshipsRelatedByFriend2'] = $this->collFriendshipsRelatedByFriend2->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFriendshipsRelatedByFriend1) {
                $result['FriendshipsRelatedByFriend1'] = $this->collFriendshipsRelatedByFriend1->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProductWishlistEntries) {
                $result['ProductWishlistEntries'] = $this->collProductWishlistEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUserId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setFirstName($value);
                break;
            case 5:
                $this->setLastName($value);
                break;
            case 6:
                $this->setLastSuccessfulLogin($value);
                break;
            case 7:
                $this->setIncorrectLoginAttempts($value);
                break;
            case 8:
                $this->setPrivelegeTypeId($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setUserId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFirstName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLastName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLastSuccessfulLogin($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setIncorrectLoginAttempts($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setPrivelegeTypeId($arr[$keys[8]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_USER_ID)) {
            $criteria->add(UserTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRST_NAME)) {
            $criteria->add(UserTableMap::COL_FIRST_NAME, $this->first_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_NAME)) {
            $criteria->add(UserTableMap::COL_LAST_NAME, $this->last_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN)) {
            $criteria->add(UserTableMap::COL_LAST_SUCCESSFUL_LOGIN, $this->last_successful_login);
        }
        if ($this->isColumnModified(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS)) {
            $criteria->add(UserTableMap::COL_INCORRECT_LOGIN_ATTEMPTS, $this->incorrect_login_attempts);
        }
        if ($this->isColumnModified(UserTableMap::COL_PRIVELEGE_TYPE_ID)) {
            $criteria->add(UserTableMap::COL_PRIVELEGE_TYPE_ID, $this->privelege_type_id);
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);
        $criteria->add(UserTableMap::COL_USER_ID, $this->user_id);

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
        $validPk = null !== $this->getUserId();

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
        return $this->getUserId();
    }

    /**
     * Generic method to set the primary key (user_id column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUserId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getUserId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setLastSuccessfulLogin($this->getLastSuccessfulLogin());
        $copyObj->setIncorrectLoginAttempts($this->getIncorrectLoginAttempts());
        $copyObj->setPrivelegeTypeId($this->getPrivelegeTypeId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFriendshipsRelatedByFriend2() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFriendshipRelatedByFriend2($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFriendshipsRelatedByFriend1() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFriendshipRelatedByFriend1($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProductWishlistEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProductWishlistEntry($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setUserId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \User Clone of current object.
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
     * Declares an association between this object and a ChildPrivilegeType object.
     *
     * @param  ChildPrivilegeType $v
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrivilegeType(ChildPrivilegeType $v = null)
    {
        if ($v === null) {
            $this->setPrivelegeTypeId(NULL);
        } else {
            $this->setPrivelegeTypeId($v->getPrivelegeTypeId());
        }

        $this->aPrivilegeType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPrivilegeType object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPrivilegeType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPrivilegeType The associated ChildPrivilegeType object.
     * @throws PropelException
     */
    public function getPrivilegeType(ConnectionInterface $con = null)
    {
        if ($this->aPrivilegeType === null && ($this->privelege_type_id !== null)) {
            $this->aPrivilegeType = ChildPrivilegeTypeQuery::create()->findPk($this->privelege_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrivilegeType->addUsers($this);
             */
        }

        return $this->aPrivilegeType;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('FriendshipRelatedByFriend2' == $relationName) {
            return $this->initFriendshipsRelatedByFriend2();
        }
        if ('FriendshipRelatedByFriend1' == $relationName) {
            return $this->initFriendshipsRelatedByFriend1();
        }
        if ('ProductWishlistEntry' == $relationName) {
            return $this->initProductWishlistEntries();
        }
    }

    /**
     * Clears out the collFriendshipsRelatedByFriend2 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFriendshipsRelatedByFriend2()
     */
    public function clearFriendshipsRelatedByFriend2()
    {
        $this->collFriendshipsRelatedByFriend2 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFriendshipsRelatedByFriend2 collection loaded partially.
     */
    public function resetPartialFriendshipsRelatedByFriend2($v = true)
    {
        $this->collFriendshipsRelatedByFriend2Partial = $v;
    }

    /**
     * Initializes the collFriendshipsRelatedByFriend2 collection.
     *
     * By default this just sets the collFriendshipsRelatedByFriend2 collection to an empty array (like clearcollFriendshipsRelatedByFriend2());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFriendshipsRelatedByFriend2($overrideExisting = true)
    {
        if (null !== $this->collFriendshipsRelatedByFriend2 && !$overrideExisting) {
            return;
        }
        $this->collFriendshipsRelatedByFriend2 = new ObjectCollection();
        $this->collFriendshipsRelatedByFriend2->setModel('\Friendship');
    }

    /**
     * Gets an array of ChildFriendship objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFriendship[] List of ChildFriendship objects
     * @throws PropelException
     */
    public function getFriendshipsRelatedByFriend2(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFriendshipsRelatedByFriend2Partial && !$this->isNew();
        if (null === $this->collFriendshipsRelatedByFriend2 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFriendshipsRelatedByFriend2) {
                // return empty collection
                $this->initFriendshipsRelatedByFriend2();
            } else {
                $collFriendshipsRelatedByFriend2 = ChildFriendshipQuery::create(null, $criteria)
                    ->filterByUserRelatedByFriend2($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFriendshipsRelatedByFriend2Partial && count($collFriendshipsRelatedByFriend2)) {
                        $this->initFriendshipsRelatedByFriend2(false);

                        foreach ($collFriendshipsRelatedByFriend2 as $obj) {
                            if (false == $this->collFriendshipsRelatedByFriend2->contains($obj)) {
                                $this->collFriendshipsRelatedByFriend2->append($obj);
                            }
                        }

                        $this->collFriendshipsRelatedByFriend2Partial = true;
                    }

                    return $collFriendshipsRelatedByFriend2;
                }

                if ($partial && $this->collFriendshipsRelatedByFriend2) {
                    foreach ($this->collFriendshipsRelatedByFriend2 as $obj) {
                        if ($obj->isNew()) {
                            $collFriendshipsRelatedByFriend2[] = $obj;
                        }
                    }
                }

                $this->collFriendshipsRelatedByFriend2 = $collFriendshipsRelatedByFriend2;
                $this->collFriendshipsRelatedByFriend2Partial = false;
            }
        }

        return $this->collFriendshipsRelatedByFriend2;
    }

    /**
     * Sets a collection of ChildFriendship objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $friendshipsRelatedByFriend2 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFriendshipsRelatedByFriend2(Collection $friendshipsRelatedByFriend2, ConnectionInterface $con = null)
    {
        /** @var ChildFriendship[] $friendshipsRelatedByFriend2ToDelete */
        $friendshipsRelatedByFriend2ToDelete = $this->getFriendshipsRelatedByFriend2(new Criteria(), $con)->diff($friendshipsRelatedByFriend2);


        $this->friendshipsRelatedByFriend2ScheduledForDeletion = $friendshipsRelatedByFriend2ToDelete;

        foreach ($friendshipsRelatedByFriend2ToDelete as $friendshipRelatedByFriend2Removed) {
            $friendshipRelatedByFriend2Removed->setUserRelatedByFriend2(null);
        }

        $this->collFriendshipsRelatedByFriend2 = null;
        foreach ($friendshipsRelatedByFriend2 as $friendshipRelatedByFriend2) {
            $this->addFriendshipRelatedByFriend2($friendshipRelatedByFriend2);
        }

        $this->collFriendshipsRelatedByFriend2 = $friendshipsRelatedByFriend2;
        $this->collFriendshipsRelatedByFriend2Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Friendship objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Friendship objects.
     * @throws PropelException
     */
    public function countFriendshipsRelatedByFriend2(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFriendshipsRelatedByFriend2Partial && !$this->isNew();
        if (null === $this->collFriendshipsRelatedByFriend2 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFriendshipsRelatedByFriend2) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFriendshipsRelatedByFriend2());
            }

            $query = ChildFriendshipQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByFriend2($this)
                ->count($con);
        }

        return count($this->collFriendshipsRelatedByFriend2);
    }

    /**
     * Method called to associate a ChildFriendship object to this object
     * through the ChildFriendship foreign key attribute.
     *
     * @param  ChildFriendship $l ChildFriendship
     * @return $this|\User The current object (for fluent API support)
     */
    public function addFriendshipRelatedByFriend2(ChildFriendship $l)
    {
        if ($this->collFriendshipsRelatedByFriend2 === null) {
            $this->initFriendshipsRelatedByFriend2();
            $this->collFriendshipsRelatedByFriend2Partial = true;
        }

        if (!$this->collFriendshipsRelatedByFriend2->contains($l)) {
            $this->doAddFriendshipRelatedByFriend2($l);
        }

        return $this;
    }

    /**
     * @param ChildFriendship $friendshipRelatedByFriend2 The ChildFriendship object to add.
     */
    protected function doAddFriendshipRelatedByFriend2(ChildFriendship $friendshipRelatedByFriend2)
    {
        $this->collFriendshipsRelatedByFriend2[]= $friendshipRelatedByFriend2;
        $friendshipRelatedByFriend2->setUserRelatedByFriend2($this);
    }

    /**
     * @param  ChildFriendship $friendshipRelatedByFriend2 The ChildFriendship object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeFriendshipRelatedByFriend2(ChildFriendship $friendshipRelatedByFriend2)
    {
        if ($this->getFriendshipsRelatedByFriend2()->contains($friendshipRelatedByFriend2)) {
            $pos = $this->collFriendshipsRelatedByFriend2->search($friendshipRelatedByFriend2);
            $this->collFriendshipsRelatedByFriend2->remove($pos);
            if (null === $this->friendshipsRelatedByFriend2ScheduledForDeletion) {
                $this->friendshipsRelatedByFriend2ScheduledForDeletion = clone $this->collFriendshipsRelatedByFriend2;
                $this->friendshipsRelatedByFriend2ScheduledForDeletion->clear();
            }
            $this->friendshipsRelatedByFriend2ScheduledForDeletion[]= $friendshipRelatedByFriend2;
            $friendshipRelatedByFriend2->setUserRelatedByFriend2(null);
        }

        return $this;
    }

    /**
     * Clears out the collFriendshipsRelatedByFriend1 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFriendshipsRelatedByFriend1()
     */
    public function clearFriendshipsRelatedByFriend1()
    {
        $this->collFriendshipsRelatedByFriend1 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFriendshipsRelatedByFriend1 collection loaded partially.
     */
    public function resetPartialFriendshipsRelatedByFriend1($v = true)
    {
        $this->collFriendshipsRelatedByFriend1Partial = $v;
    }

    /**
     * Initializes the collFriendshipsRelatedByFriend1 collection.
     *
     * By default this just sets the collFriendshipsRelatedByFriend1 collection to an empty array (like clearcollFriendshipsRelatedByFriend1());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFriendshipsRelatedByFriend1($overrideExisting = true)
    {
        if (null !== $this->collFriendshipsRelatedByFriend1 && !$overrideExisting) {
            return;
        }
        $this->collFriendshipsRelatedByFriend1 = new ObjectCollection();
        $this->collFriendshipsRelatedByFriend1->setModel('\Friendship');
    }

    /**
     * Gets an array of ChildFriendship objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFriendship[] List of ChildFriendship objects
     * @throws PropelException
     */
    public function getFriendshipsRelatedByFriend1(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFriendshipsRelatedByFriend1Partial && !$this->isNew();
        if (null === $this->collFriendshipsRelatedByFriend1 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFriendshipsRelatedByFriend1) {
                // return empty collection
                $this->initFriendshipsRelatedByFriend1();
            } else {
                $collFriendshipsRelatedByFriend1 = ChildFriendshipQuery::create(null, $criteria)
                    ->filterByUserRelatedByFriend1($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFriendshipsRelatedByFriend1Partial && count($collFriendshipsRelatedByFriend1)) {
                        $this->initFriendshipsRelatedByFriend1(false);

                        foreach ($collFriendshipsRelatedByFriend1 as $obj) {
                            if (false == $this->collFriendshipsRelatedByFriend1->contains($obj)) {
                                $this->collFriendshipsRelatedByFriend1->append($obj);
                            }
                        }

                        $this->collFriendshipsRelatedByFriend1Partial = true;
                    }

                    return $collFriendshipsRelatedByFriend1;
                }

                if ($partial && $this->collFriendshipsRelatedByFriend1) {
                    foreach ($this->collFriendshipsRelatedByFriend1 as $obj) {
                        if ($obj->isNew()) {
                            $collFriendshipsRelatedByFriend1[] = $obj;
                        }
                    }
                }

                $this->collFriendshipsRelatedByFriend1 = $collFriendshipsRelatedByFriend1;
                $this->collFriendshipsRelatedByFriend1Partial = false;
            }
        }

        return $this->collFriendshipsRelatedByFriend1;
    }

    /**
     * Sets a collection of ChildFriendship objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $friendshipsRelatedByFriend1 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFriendshipsRelatedByFriend1(Collection $friendshipsRelatedByFriend1, ConnectionInterface $con = null)
    {
        /** @var ChildFriendship[] $friendshipsRelatedByFriend1ToDelete */
        $friendshipsRelatedByFriend1ToDelete = $this->getFriendshipsRelatedByFriend1(new Criteria(), $con)->diff($friendshipsRelatedByFriend1);


        $this->friendshipsRelatedByFriend1ScheduledForDeletion = $friendshipsRelatedByFriend1ToDelete;

        foreach ($friendshipsRelatedByFriend1ToDelete as $friendshipRelatedByFriend1Removed) {
            $friendshipRelatedByFriend1Removed->setUserRelatedByFriend1(null);
        }

        $this->collFriendshipsRelatedByFriend1 = null;
        foreach ($friendshipsRelatedByFriend1 as $friendshipRelatedByFriend1) {
            $this->addFriendshipRelatedByFriend1($friendshipRelatedByFriend1);
        }

        $this->collFriendshipsRelatedByFriend1 = $friendshipsRelatedByFriend1;
        $this->collFriendshipsRelatedByFriend1Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Friendship objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Friendship objects.
     * @throws PropelException
     */
    public function countFriendshipsRelatedByFriend1(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFriendshipsRelatedByFriend1Partial && !$this->isNew();
        if (null === $this->collFriendshipsRelatedByFriend1 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFriendshipsRelatedByFriend1) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFriendshipsRelatedByFriend1());
            }

            $query = ChildFriendshipQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByFriend1($this)
                ->count($con);
        }

        return count($this->collFriendshipsRelatedByFriend1);
    }

    /**
     * Method called to associate a ChildFriendship object to this object
     * through the ChildFriendship foreign key attribute.
     *
     * @param  ChildFriendship $l ChildFriendship
     * @return $this|\User The current object (for fluent API support)
     */
    public function addFriendshipRelatedByFriend1(ChildFriendship $l)
    {
        if ($this->collFriendshipsRelatedByFriend1 === null) {
            $this->initFriendshipsRelatedByFriend1();
            $this->collFriendshipsRelatedByFriend1Partial = true;
        }

        if (!$this->collFriendshipsRelatedByFriend1->contains($l)) {
            $this->doAddFriendshipRelatedByFriend1($l);
        }

        return $this;
    }

    /**
     * @param ChildFriendship $friendshipRelatedByFriend1 The ChildFriendship object to add.
     */
    protected function doAddFriendshipRelatedByFriend1(ChildFriendship $friendshipRelatedByFriend1)
    {
        $this->collFriendshipsRelatedByFriend1[]= $friendshipRelatedByFriend1;
        $friendshipRelatedByFriend1->setUserRelatedByFriend1($this);
    }

    /**
     * @param  ChildFriendship $friendshipRelatedByFriend1 The ChildFriendship object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeFriendshipRelatedByFriend1(ChildFriendship $friendshipRelatedByFriend1)
    {
        if ($this->getFriendshipsRelatedByFriend1()->contains($friendshipRelatedByFriend1)) {
            $pos = $this->collFriendshipsRelatedByFriend1->search($friendshipRelatedByFriend1);
            $this->collFriendshipsRelatedByFriend1->remove($pos);
            if (null === $this->friendshipsRelatedByFriend1ScheduledForDeletion) {
                $this->friendshipsRelatedByFriend1ScheduledForDeletion = clone $this->collFriendshipsRelatedByFriend1;
                $this->friendshipsRelatedByFriend1ScheduledForDeletion->clear();
            }
            $this->friendshipsRelatedByFriend1ScheduledForDeletion[]= $friendshipRelatedByFriend1;
            $friendshipRelatedByFriend1->setUserRelatedByFriend1(null);
        }

        return $this;
    }

    /**
     * Clears out the collProductWishlistEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addProductWishlistEntries()
     */
    public function clearProductWishlistEntries()
    {
        $this->collProductWishlistEntries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collProductWishlistEntries collection loaded partially.
     */
    public function resetPartialProductWishlistEntries($v = true)
    {
        $this->collProductWishlistEntriesPartial = $v;
    }

    /**
     * Initializes the collProductWishlistEntries collection.
     *
     * By default this just sets the collProductWishlistEntries collection to an empty array (like clearcollProductWishlistEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProductWishlistEntries($overrideExisting = true)
    {
        if (null !== $this->collProductWishlistEntries && !$overrideExisting) {
            return;
        }
        $this->collProductWishlistEntries = new ObjectCollection();
        $this->collProductWishlistEntries->setModel('\ProductWishlistEntry');
    }

    /**
     * Gets an array of ChildProductWishlistEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildProductWishlistEntry[] List of ChildProductWishlistEntry objects
     * @throws PropelException
     */
    public function getProductWishlistEntries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProductWishlistEntriesPartial && !$this->isNew();
        if (null === $this->collProductWishlistEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProductWishlistEntries) {
                // return empty collection
                $this->initProductWishlistEntries();
            } else {
                $collProductWishlistEntries = ChildProductWishlistEntryQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collProductWishlistEntriesPartial && count($collProductWishlistEntries)) {
                        $this->initProductWishlistEntries(false);

                        foreach ($collProductWishlistEntries as $obj) {
                            if (false == $this->collProductWishlistEntries->contains($obj)) {
                                $this->collProductWishlistEntries->append($obj);
                            }
                        }

                        $this->collProductWishlistEntriesPartial = true;
                    }

                    return $collProductWishlistEntries;
                }

                if ($partial && $this->collProductWishlistEntries) {
                    foreach ($this->collProductWishlistEntries as $obj) {
                        if ($obj->isNew()) {
                            $collProductWishlistEntries[] = $obj;
                        }
                    }
                }

                $this->collProductWishlistEntries = $collProductWishlistEntries;
                $this->collProductWishlistEntriesPartial = false;
            }
        }

        return $this->collProductWishlistEntries;
    }

    /**
     * Sets a collection of ChildProductWishlistEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $productWishlistEntries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setProductWishlistEntries(Collection $productWishlistEntries, ConnectionInterface $con = null)
    {
        /** @var ChildProductWishlistEntry[] $productWishlistEntriesToDelete */
        $productWishlistEntriesToDelete = $this->getProductWishlistEntries(new Criteria(), $con)->diff($productWishlistEntries);


        $this->productWishlistEntriesScheduledForDeletion = $productWishlistEntriesToDelete;

        foreach ($productWishlistEntriesToDelete as $productWishlistEntryRemoved) {
            $productWishlistEntryRemoved->setUser(null);
        }

        $this->collProductWishlistEntries = null;
        foreach ($productWishlistEntries as $productWishlistEntry) {
            $this->addProductWishlistEntry($productWishlistEntry);
        }

        $this->collProductWishlistEntries = $productWishlistEntries;
        $this->collProductWishlistEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProductWishlistEntry objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ProductWishlistEntry objects.
     * @throws PropelException
     */
    public function countProductWishlistEntries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collProductWishlistEntriesPartial && !$this->isNew();
        if (null === $this->collProductWishlistEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProductWishlistEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProductWishlistEntries());
            }

            $query = ChildProductWishlistEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collProductWishlistEntries);
    }

    /**
     * Method called to associate a ChildProductWishlistEntry object to this object
     * through the ChildProductWishlistEntry foreign key attribute.
     *
     * @param  ChildProductWishlistEntry $l ChildProductWishlistEntry
     * @return $this|\User The current object (for fluent API support)
     */
    public function addProductWishlistEntry(ChildProductWishlistEntry $l)
    {
        if ($this->collProductWishlistEntries === null) {
            $this->initProductWishlistEntries();
            $this->collProductWishlistEntriesPartial = true;
        }

        if (!$this->collProductWishlistEntries->contains($l)) {
            $this->doAddProductWishlistEntry($l);
        }

        return $this;
    }

    /**
     * @param ChildProductWishlistEntry $productWishlistEntry The ChildProductWishlistEntry object to add.
     */
    protected function doAddProductWishlistEntry(ChildProductWishlistEntry $productWishlistEntry)
    {
        $this->collProductWishlistEntries[]= $productWishlistEntry;
        $productWishlistEntry->setUser($this);
    }

    /**
     * @param  ChildProductWishlistEntry $productWishlistEntry The ChildProductWishlistEntry object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeProductWishlistEntry(ChildProductWishlistEntry $productWishlistEntry)
    {
        if ($this->getProductWishlistEntries()->contains($productWishlistEntry)) {
            $pos = $this->collProductWishlistEntries->search($productWishlistEntry);
            $this->collProductWishlistEntries->remove($pos);
            if (null === $this->productWishlistEntriesScheduledForDeletion) {
                $this->productWishlistEntriesScheduledForDeletion = clone $this->collProductWishlistEntries;
                $this->productWishlistEntriesScheduledForDeletion->clear();
            }
            $this->productWishlistEntriesScheduledForDeletion[]= $productWishlistEntry;
            $productWishlistEntry->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ProductWishlistEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildProductWishlistEntry[] List of ChildProductWishlistEntry objects
     */
    public function getProductWishlistEntriesJoinProduct(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildProductWishlistEntryQuery::create(null, $criteria);
        $query->joinWith('Product', $joinBehavior);

        return $this->getProductWishlistEntries($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aPrivilegeType) {
            $this->aPrivilegeType->removeUser($this);
        }
        $this->user_id = null;
        $this->username = null;
        $this->password = null;
        $this->email = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->last_successful_login = null;
        $this->incorrect_login_attempts = null;
        $this->privelege_type_id = null;
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
            if ($this->collFriendshipsRelatedByFriend2) {
                foreach ($this->collFriendshipsRelatedByFriend2 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFriendshipsRelatedByFriend1) {
                foreach ($this->collFriendshipsRelatedByFriend1 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProductWishlistEntries) {
                foreach ($this->collProductWishlistEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFriendshipsRelatedByFriend2 = null;
        $this->collFriendshipsRelatedByFriend1 = null;
        $this->collProductWishlistEntries = null;
        $this->aPrivilegeType = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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