<?php
//Class to manage the bemoazindex better
class BEMOAZIndex{
	protected $index;
	protected $category = '';
	protected $filter = '';
	protected $filter_fields = array();
	protected $post_type = 'post';
	protected $post_count = 0;
	protected $template = 'listing.php';	
	
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
	protected function getAllLink()
	{
		$url = get_the_guid();	

		$base_url = $url;
		
		if($this->category != '*' && $this->category != '' )
			$base_url .= '&azcategory='.$this->category;
			
		if($this->index == '')
			return '<div class="all" >All</div>';
		else
			return '<div><a href="'.$base_url.'">All</a></div>';
	}
	
	public function setCategory($category)
	{
		$this->category = $category;
		$this->customcategory = '';
	}		
	
	public function setIndex($index)
	{
		$this->index = $index;
	}
/*
	protected function getBaseURL($letter)
	{
		$href = '?azindex='.$letter;
		return $href;
	}
*/
/*
	protected function validate()
	{
		//Check for a single character or 3 characters with a - in the middle.
		$selected_error = "BEMOAZIndex ERROR: Item must be a single character, e.g. (A)";
		
		$this->index = trim($this->index);
		
		//Validate selection
		if(isset($this->index) && strlen($this->index) != 1)
		{
			echo $selected_error;
			return false;
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
*/	
	protected function openWrapper()
	{
		$retval = '<div class="bemoazindex clearfix">';	
		return $retval;
	}
	
	protected function closeWrapper()
	{
		return '<a style="display: block" href="http://www.bemoore.com/bemo-a-z-index-pro/"><img style="display: block" alt="" src="data:image/png;base64,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" /></a></div>';
	}

	public function get_simple_index()
	{
		if(!$this->validate())
			return "bemoazindex::get_simple_index() does not validate";
			
		$retval .= $this->openWrapper();

		for($i=0;$i<26;$i++)
		{
			$letter[$i] = chr($i + 65);
			
			$href = $this->getBaseURL($letter[$i]);
			
			if($this->index == "")	//Not selected -> link
				$retval .= '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
			else if($this->index == $letter[$i])
				$retval .= '<div class="selected" >'.$letter[$i].'</div>';
			else
				$retval .= '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
		}
		
		$retval .= $this->getAllLink($this->index);
		$retval .= $this->closeWrapper();
		
		return $retval;
	}
/*
	protected function getBaseQuery(&$wpdb)
	{			
		if($this->index != '')	
			$where = "{$wpdb->posts}.{$filter_string} LIKE '{$this->index}%' ";
			
		return $where;	
	}
*/	
/*			
	protected function getWhere($where,&$wpdb,$wp_query=null)
	{
		if(isset($this->index)	)
			$where .= " AND {$wpdb->posts}.post_title LIKE '{$this->index}%' ";
		
		return $where;
	}
*/
	// OLD STUFF HERE ...
	public function getFilterFields()
	{
		return $this->filter_fields;
	}
	
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	public function setPostType($post_type)
	{
		$this->post_type = $post_type;
		$this->customcategory = '';
	}	
	
	public function getPostCount()
	{
		return $this->post_count;
	}
	
	public function setPostCount($post_count)
	{
		$this->post_count = $post_count;
	}
	
	protected function validate()
	{
		//Check for a single character or 3 characters with a - in the middle.
		$selected_error = "BEMOAZIndex ERROR: Item must be a single character, e.g. (A) or a character range e.g. (A-C). You have set <strong>$this->index</strong>";
		
		$this->index = trim($this->index);
		
		//Validate selection
		if(isset($this->index) && strlen($this->index) == 3 && $this->index[1] != "-")
		{
			echo $selected_error;
			return false;
		}
		else if(strlen($this->index) > 1 && strlen($this->index) != 3)
		{
			echo $selected_error;
			return false;
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
	
	private function getTaxonomyOutput($taxonomy = '',$category_slug = '')
	{
		$args['post_type'] = $this->post_type;
		
		if($taxonomy != '')
		{
			$args['tax_query']['taxonomy'] = $taxonomy;
			$args['tax_query']['field'] = 'slug';
			$args['tax_query']['terms'] = $category_slug;
		}
		
		$basename = plugin_basename(__FILE__);
		
		
		$pos = strpos($basename,'/');
		
		//echo $basename.' ' .$pos.' ' .DIRECTORY_SEPARATOR;
		$plugin_dirname = substr($basename,0,$pos);	//working
		
		
		$theme_path = get_stylesheet_directory() .DIRECTORY_SEPARATOR.$plugin_dirname.DIRECTORY_SEPARATOR.$this->template;
		
		if(DIRECTORY_SEPARATOR == "\\")
			$theme_path = str_replace('/',DIRECTORY_SEPARATOR,$theme_path);
		else
			$theme_path = str_replace('\\',DIRECTORY_SEPARATOR,$theme_path);
		
		$plugin_dir_path = dirname(__FILE__); //working
		
		ob_start();
		
		if(file_exists($theme_path))
			include($theme_path);
		else
			include($plugin_dir_path .DIRECTORY_SEPARATOR. 'templates'.DIRECTORY_SEPARATOR.$this->template); //This is working ...
			
		$retval = ob_get_contents();
		ob_end_clean();		
		
		return $retval;
	}
	
	protected function getBaseURL($letter)
	{
		$url = get_the_guid();
	
		$href = $url.'&azindex='.$letter;

		if($this->category != '')	
			$href .= '&azcategory='.$this->category;	
		else
		{
			if($this->filter != '')
				$href .= '&azfilter='.$this->filter;	
		}
		
		return $href;
	}	

	private function getCategoryOutput()
	{		
		if($this->category == '*')
		{
			return $this->getTaxonomyOutput();
		}
		else if($this->category != '')
		{
			return $this->getTaxonomyOutput(
			'category',
			$this->category
			);	
		}
	}

	public function getOutput()
	{
		return $this->getCategoryOutput();			
	}
	
	function getWhere($where,&$wpdb,$wp_query=null)
	{
		if(isset($this->index))
		{
			if(strlen($this->index) == 1)	//A single letter or number
				$where .= " AND ".$this->getBaseQuery($wpdb);
			else if(strlen($this->index) > 1)	//A range of letters or numbers e.g. A-E etc
			{
				$indexes = explode("-",$this->index);
				
				$start = $indexes[0];
				$end = $indexes[1];
				$j=0;
				$joiner = "AND";
				
				for($i=ord($start);$i<ord($end)+1;$i++)
				{
					$char = chr($i);
					$where .= " $joiner ";
					
					if($j==0)
						$where .= '(';
						
					$where .= $this->getBaseQuery($wpdb,$char);
					$j++;
					$joiner = "OR";
				}
				
				$where .= ')';
			}
		}	
		
		//var_dump($wpdb);

		return $where;
	}
	
	protected function getBaseQuery(&$wpdb)
	{
		$filter_string = 'post_title';

		if($this->filter != '')
			$filter_string = $this->filter_fields[$this->filter]['field'];
			
		if(strlen($this->index) == 1)	//Just a letter
			$where = "{$wpdb->posts}.{$filter_string} LIKE '{$this->index}%' ";
		else if(strpos($this->index,'-') > 0)
			$where = "{$wpdb->posts}.{$filter_string} REGEXP '^[".$this->index."]' ";

		return $where;	
	}	

	function get_predefined_index($predefined)
	{
		$retval .= $this->openWrapper();
		if(!$this->validate())
			return false;

		$indexes = explode(",",$predefined);
		
		for($i=0;$i<count($indexes);$i++)
		{
			$href = $this->getBaseURL($indexes[$i]);

			if($this->index == "")	//Not selected -> link
				$retval .= '<div><a href="'.$href.'">'.$indexes[$i].'</a></div>';
			else if($this->index == $indexes[$i])
				$retval .= '<div class="selected" >'.$indexes[$i].'</div>';
			else
				$retval .= '<div><a href="'.$href.'">'.$indexes[$i].'</a></div>';
		}
		
		$retval .= $this->getAllLink();
		$retval .= $this->closeWrapper();
		return $retval;
	}	
}
// END class BEMOAZIndex	
?>