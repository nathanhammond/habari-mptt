<?php
/**
* Habari Modified Preorder Tree Traversal (MPTT) Class
* Creates an object that acts as the parent of all MPTT nodes for a specified table.
* 
* @package Habari
*/
class MPTTNode
{

	public $mptt;

	public $id;
	public $mptt_left;
	public $mptt_right;
	public $mptt_parent;

	private $last;

	public function __construct( $mptt, $node = array(), $last = false )
	{
		// Set the relationship to the table.
		$this->mptt = $mptt;

		// Collate node information.
		$this->id = $node['id'];
		$this->mptt_left = $node['mptt_left'];
		$this->mptt_right = $node['mptt_right'];
		$this->mptt_parent = $node['mptt_parent'];

		// Set this node up for chaining.
		$this->last = $last;
	}

	/* Chaining. */

	public function end()
	{
		return $this->last;
	}

	/* Attributes. */

	public function depth()
	{
		
	}

	/* Traversal methods. */

	public function parent()
	{
		$result = DB::get_row('SELECT * FROM '.$this->mptt->table.' WHERE id = '.$this->mptt_parent.';');
		if ($result) {
			return new MPTTNode($this->mptt, $result->to_array(), $this);
		} else {
			return false;
		}
	}

	public function child( $n )
	{
		$result = DB::get_row('SELECT * FROM '.$this->mptt->table.' WHERE mptt_parent = '.$this->id.' ORDER BY mptt_left LIMIT '.($n-1).', 1;');
		if ($result) {
			return new MPTTNode($this->mptt, $result->to_array(), $this);
		} else {
			return false;
		}
	}

	public function prev()
	{
		$result = DB::get_row('SELECT * FROM '.$this->mptt->table.' WHERE mptt_parent = '.$this->mptt_parent.' AND mptt_left = '.$this->mptt_left.' - 2;');
		if ($result) {
			return new MPTTNode($this->mptt, $result->to_array(), $this);
		} else {
			return false;
		}
	}

	public function next()
	{
		$result = DB::get_row('SELECT * FROM '.$this->mptt->table.' WHERE mptt_parent = '.$this->mptt_parent.' AND mptt_left = '.$this->mptt_left.' + 2;');
		if ($result) {
			return new MPTTNode($this->mptt, $result->to_array(), $this);
		} else {
			return false;
		}
	}

	public function prevAll( $self = false )
	{
		// SELECT * FROM $this->table WHERE mptt_parent = $this->mptt_parent AND mptt_left < $this->mptt_left ORDER BY mptt_left;
		// Returns MPTTSet
	}

	public function nextAll( $self = false )
	{
		// SELECT * FROM $this->table WHERE mptt_parent = $this->mptt_parent AND mptt_left > $this->mptt_left ORDER BY mptt_left;
		// Returns MPTTSet
	}

	public function siblings( $self = false )
	{
		// SELECT * FROM $this->table WHERE mptt_parent = $this->mptt_parent ORDER BY mptt_left;
		// Returns MPTTSet
	}

	public function children()
	{
		// SELECT * FROM $this->table WHERE mptt_parent = $this->id ORDER BY mptt_left ASC;
		// Returns MPTTSet
	}

	public function ancestors( $self = false )
	{
		// SELECT parent.* FROM $this->table AS node, $this->table AS parent WHERE node.mptt_left BETWEEN parent.mptt_left AND parent.mptt_right AND node.id = $this->id ORDER BY parent.mptt_left ASC;
		// Returns MPTTSet
	}

	public function descendants( $self = false )
	{
		// TODO
		// Returns MPTTSet
	}

	/* Modification. */

	public function append( $node, $sort = false )
	{
		
	}

	public function append_to( $node, $sort = false )
	{
		
	}

	public function insert_before( $node )
	{
		
	}

	public function insert_after( $node )
	{
		
	}

	public function remove( $nodeID )
	{
		
	}
}
?>
