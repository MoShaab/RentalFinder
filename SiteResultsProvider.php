<?php

class SiteResultsProvider {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function getNumResults($term) {

		$query = $this->db->prepare("SELECT COUNT(*) as total
										 FROM house WHERE house_name LIKE ?
										 OR street LIKE ? 
										 OR description LIKE ?");

		$searchTerm = "%". $term . "%";
		$query->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
		$query->execute();

		$result = $query->get_result();
		$row = $result->fetch_assoc();
		return $row["total"];

	}

	public function getResultsHtml($page, $pageSize, $term){

		$query = $this->db->prepare("SELECT * 
										 FROM house WHERE house_name LIKE ?
										 OR street LIKE ? 
										 OR description LIKE ?");

		$searchTerm = "%". $term . "%";
		$query->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
		$query->execute();

		$result = $query->get_result();

		$resultsHtml = "<div class = 'searchResults'>";
		while ($row = $result->fetch_assoc()){
			
			$house_name = $row["house_name"];
			$street = $row["street"];
			$image = $row["image"];
			$description = $row["description"];
			$monthly_rent = $row["monthly_rent"];
			$phone = $row["phone"];
			$resultsHtml .= "<div class = 'resultsContainer'>
								<h3 class = 'house_name'>
									<a href = ''> $house_name</a>
								</h3>
								<img class = 'img' src='./img/" . $image . "'>
								<span class = 'result'>$street</span>
								<span class = 'result'>Ksh $monthly_rent per month</span>
								<span class = 'result'>$description</span>
								<span class = 'result'>Call us on $phone</span>

			                </div>";
		}

		$resultsHtml .= "</div>";
		return $resultsHtml;
	}
}
?>
