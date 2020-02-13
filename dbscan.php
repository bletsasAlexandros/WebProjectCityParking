<?php

class DBSCAN {
	
	private $points;
	
	private $distance_matrix; 
	
	private $noise_points;  
	private $in_a_cluster;
	private $clusters;
	
	public function __construct($distance_matrix, $points)
	{
		$this->distance_matrix = $distance_matrix;
		$this->points = $points;
		$this->noise_points = array();
		$this->clusters = array();
		$this->in_a_cluster = array();
	}
	
	public function set_points($new_points)
	{
		$this->points = $new_points;
	}
	
	private function expand_cluster($point, $neighbor_points, $c, $epsilon, $min_points)
	{
		$this->clusters[$c][] = $point;
		$this->in_a_cluster[] = $point;
		$neighbor_point = reset($neighbor_points);
		while ($neighbor_point)
		{
			$neighbor_points2 = $this->region_query($neighbor_point, $epsilon);
			if (count($neighbor_points2) >= $min_points)
			{
				foreach ($neighbor_points2 as $neighbor_point2)
				{
					if (!in_array($neighbor_point2, $neighbor_points))
					{
						$neighbor_points[] = $neighbor_point2;
					}
				}
			}
			if (!in_array($neighbor_point, $this->in_a_cluster))
			{
				$this->clusters[$c][] = $neighbor_point;
				$this->in_a_cluster[] = $neighbor_point;
			}
			$neighbor_point = next($neighbor_points);
		}
	}
	
	private function region_query($point, $epsilon)
	{
		$neighbor_points = array();
		
		foreach ($this->points as $point2)
		{
			if ($point != $point2)
			{
				
				if (array_key_exists($point2, $this->distance_matrix[$point]))
				{	
					$distance = $this->distance_matrix[$point][$point2];
				} else {
					$distance = $this->distance_matrix[$point2][$point];
				}
				if ($distance < $epsilon)
				{
					$neighbor_points[] = $point2;
				}
			
			}
		}
		return $neighbor_points;
	}
	
	
	public function dbscan($epsilon, $min_points)  
	{
		$this->noise_points = array();  
		$this->clusters = array();		
		$this->in_a_cluster = array();  
		
		$c = 0;
		$this->clusters[$c] = array();
		foreach ($this->points as $point_id)
		{
			$neighbor_points = $this->region_query($point_id, $epsilon);
			if (count($neighbor_points) < $min_points)
			{
				$this->noise_points[] = $point_id;
			} elseif (!in_array($point_id, $this->in_a_cluster)) {
				$this->expand_cluster($point_id, $neighbor_points, $c, $epsilon, $min_points);
				$c = $c + 1;
				$this->clusters[$c] = array();
			}
		}
		
		return $this->clusters;
	}
}
?>