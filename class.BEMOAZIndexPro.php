<?php
require_once('class.BEMOAZIndex.php');

class BEMOAZIndexPro extends BEMOAZIndex
{
	protected $filter = '';
	protected $filter_fields = array();
	protected $category = '';
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

	public function setCategory($category)
	{
		$this->category = $category;
		$this->customcategory = '';
	}	

	public function setPostType($post_type)
	{
		$this->post_type = $post_type;
		$this->customcategory = '';
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
			return $this->getTaxonomyOutput();
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
?>