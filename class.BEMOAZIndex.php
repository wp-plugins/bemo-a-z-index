<?php
//Class to manage the bemoazindex better
class BEMOAZIndex{

	protected $filter = '';
	protected $filter_fields = array();
	protected $customcategory = '';

	function __construct()
	{
		$this->filter_fields['title']['field'] = 'post_title';
		$this->filter_fields['content']['field'] = 'post_content';	
		$this->filter_fields['excerpt']['field'] = 'post_excerpt';	
		$this->filter_fields['slug']['field'] = 'post_name';
		
		$this->filter_fields['title']['name'] = 'Title';
		$this->filter_fields['content']['name'] = 'Content';	
		$this->filter_fields['excerpt']['name'] = 'Excerpt';	
		$this->filter_fields['slug']['name'] = 'Slug';
	}

	public function getFilterFields()
	{
		return $this->filter_fields;
	}

	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	public function setCustomCategory($customcategory)
	{
		$this->customcategory = $customcategory;
	}
	protected function getAllLink($selected)
	{
		$url = $_SERVER["REQUEST_URI"];
		
		$pos = strpos($url,'?azindex');
		
		$base_url = substr($url,0,$pos);

		if($selected == -1)
			echo '<div class="all" >All</div>';
		else
			echo '<div><a href="'.$base_url.'">All</a></div>';
	}

	protected function getBaseURL($letter)
	{
		$href = '?azindex='.$letter;

		if($this->filter != '')
			$href .= '&azfilter='.$this->filter;	
		
		return $href;
	}

	protected function validate($selected)
	{
		//Check for a single character or 3 characters with a - in the middle.
		$selected_error = "BEMOAZIndex ERROR: Item must be a single character, e.g. (A) or a character range e.g. (A-C) ";
		
		$selected = trim($selected);
		
		//Validate selection
		if(isset($selected) && $selected != '-1')
		{
			if(strlen($selected) > 3)
			{
				echo $selected_error;
				return false;
			}
			else if(strlen($selected) == 3)
			{
				if($selected[1] != '-')
				{
					echo $selected_error;
					return false;
				}
			}
			else if(strlen($selected) == 2)
			{
				echo $selected_error;
			}
		}
		
		//Validate filter
		if($this->filter != '')
		{
			if(!isset($this->filter_fields[$this->filter]['field']))
			{
				
				echo 'BEMOAZIndex ERROR: filter parameter must be one of ';
				
				$i=0;
				
				foreach($this->filter_fields as $k => $v)
				{
					if($i>0)
						echo ',';
						
					echo $v['name'];
					$i++;
				}
				
				echo '.';

				return false;
			}
		}

		return true;
	}

	public function get_simple_index($selected)
	{
		if(!$this->validate($selected))
			return false;

		for($i=0;$i<26;$i++)
		{
			$letter[$i] = chr($i + 65);
			
			$href = $this->getBaseURL($letter[$i]);
			
			if($selected == "")	//Not selected -> link
				echo '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
			else if($selected == $letter[$i])
				echo '<div class="selected" >'.$letter[$i].'</div>';
			else
				echo '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
		}
		
		$this->getAllLink($selected);
	}


	protected function getBaseQuery($wpdb,$key)
	{
		$filter_string = 'post_title';

		if($this->filter != '')
			$filter_string = $this->filter_fields[$this->filter]['field'];
			
		$where = "{$wpdb->posts}.{$filter_string} LIKE '{$key}%' ";
			
		return $where;	
	}
				
	public function getWhere($where,$bemoazindex,$wpdb,$wp_query=null)
	{
		if(isset($bemoazindex)	)
			$where .= " AND {$wpdb->posts}.post_title LIKE '{$bemoazindex}%' ";
		
		return $where;
	}
}
// END class BEMOAZIndex
?>