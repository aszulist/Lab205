<?php

	class Lab{
		
		private $p;
		private $q;
		private $n;
		private $totient_n;
		private $e;
		private $d;
		
		public function __construct($p, $q){
			
			$this->p = $p;
			$this->q = $q;
			
			$this->n = $p * $q;
			
			$this->totient_n = $this->generateTotient();
			
			$this->e = $this->getValueE($this->q, $this->totient_n);
			
			$this->d = $this->getValueD($this->e, $this->totient_n);
			
		}
		
		public function getP(){
			return $this->p;
		}
		public function getQ(){
			return $this->q;
		}
		public function getN(){
			return $this->n;
		}
		public function getTotient(){
			return $this->totient_n;
		}
		public function getE(){
			return $this->e;
		}
		public function getD(){
			return $this->d;
		}
		
		//Generate totient from prime number (p and q must be prime numbers)
		public function generateTotient(){
			return ($this->p - 1) * ($this->q - 1);
		}
		
		//Function to generate E value (needed to public key)
		private function getValueE($q, $totient){
			
			$E = $q+1;
			
			while ($this->getNWD($E, $totient) != 1){
				$E++;
			}
			return $E;
		}
		
		//Function to generate D value (needed to private key)
		private function getValueD($E, $totient){
			
			$D = 1;
			
			while (($D * $E) % $totient != 1){
				$D++;
			}
			return $D;
		}
		
		//Function to get NWD
		private function getNWD($value_1, $value_2){
			while ($value_1 != $value_2) {
				if ($value_1 < $value_2) {
				   $pom = $value_1; $value_1 = $value_2; $value_2 = $pom;
				} 
				$value_1 = $value_1 - $value_2;
			}
			return $value_1;
		}
		
		public function getValuesInformations(){
			$html = 
				"p = {$this->p}<br />".
				"q = {$this->q}<br />".
				"n = {$this->n}<br />".
				"totient n = {$this->totient_n}<br />".
				"E = {$this->e}<br />".
				"D = {$this->d}<br />";
			
			return $html;
		}
		
	}
	
	class LabEncrypter{
		
		//Change message into array with ASCII values
		public static function getAsciiValuesFromString($string){
			$array = array();
			$string_length = strlen($string);
			for($i=0; $i < $string_length; $i++){
				$char = $string[$i];
				$array[$char] = ord($char);
			}
			return $array;
		}
		
		//Encrypt array with ASCII values
		public static function encryptCharsArray($array, $e, $n){
			$new_array = array();
			foreach($array as $key => $value){
				$encrypted_char = chr(self::encryptEachChar($value, $e, $n));
				$new_array[$encrypted_char] = self::encryptEachChar($value, $e, $n);
			}
			return $new_array;
		}
		
		//Encrypt each char
		private static function encryptEachChar($char, $e, $n){
			return (int)(bcpowmod($char, $e, $n));
		}
		
		//Decrypt array with encryoted ASCII values
		public static function decryptCharsArray($array, $d, $n){
			$new_array = array();
			foreach($array as $key => $value){
				$decrypted_char = chr(self::decryptEachChar($value, $d, $n));
				$new_array[$decrypted_char] = self::decryptEachChar($value, $d, $n);
			}
			return $new_array;
		}
		
		//Decrypt each char
		private static function decryptEachChar($char, $d, $n){
			return (int)(bcpowmod($char, $d, $n));
		}
		
	}

	//rendering information when use first form
	function renderData(){
		
		//if button was clicked
		if(isset($_POST['load_values'])){
		
			//Numbers taken from form (take them from pdf)
			$p = $_POST['p_value'];
			$q = $_POST['q_value'];
			
			//message taken from form (take it from pdf)
			$message = $_POST['message'];
			
			//simple validation
			if(isset($p) && !empty($p) && is_numeric($p) && checkIfPrimeNumber($p) &&
				isset($q) && !empty($q) && is_numeric($q) && checkIfPrimeNumber($q) &&
				isset($message) && !empty($message)){	
				
				//create lab object
				$Lab = new Lab($p, $q);
				
				$n = $Lab->getN();
				$E = $Lab->getE();
				$D = $Lab->getD();
				
				//show informations
				echo $Lab->getValuesInformations();
				
				//Change string value to array with ASCII values
				$values_array = LabEncrypter::getAsciiValuesFromString($message);
				
				//Encrypt message array
				$encrypted_array = LabEncrypter::encryptCharsArray($values_array, $E, $n);
				
				//Show encrypted array
				echo "<p> Encrypt </p>";
				echo generateTableToRenderArray($encrypted_array);
				
				//Decrypt message array
				$decrypted_array = LabEncrypter::decryptCharsArray($encrypted_array, $D, $n);
				
				//Show decrypted array
				echo "<p> Dencrypt </p>";
				echo generateTableToRenderArray($decrypted_array);
				
			} else {
				
				//When user put incorrect data in form
				echo "Incorrect data !";
			}
		}
	}
	
	function checkIfPrimeNumber($n){
			for($i = $n-1; $i >1; $i--){
				if($n % $i == 0){
					return false;
				}
			}
			return true;
	}
	
	function generateTableToRenderArray($array){
		$html = '<table>'.
					'<tr>'.
						'<th>Character</th>'.
						'<th>ASCII</th>'.
					'</tr>';
		foreach($array as $key => $value){
			$html .= '<tr>'.
						"<td>{$key}</td>".
						"<td>{$value}</td>".
					'</tr>';
		}		
		
		$html .= '</table>';
		
		return $html;
	}
	
	
	
	