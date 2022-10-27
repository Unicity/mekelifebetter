var app = angular.module('myApp', []);

app.controller('ssnController', function($scope, $http, $location) {
	var isValidName = false;

	$scope.getDistName = function (){
		$http({
			method: "GET",
			accept: "application/json",
			url: "https://hydra.unicity.net/v5a/customers?unicity="+$scope.distID+"&expand=customer"
		}).then(function success(response){
			$scope.IDError = "";
			$scope.distName = response.data.items[0].humanName['fullName@ko'];
			isValidName = true;
		}, function error(response){
			$scope.distName="";
			$scope.IDError = "입력하신 회원정보를 찾을 수 없습니다. 회원번호를 다시 확인해 주십시오.";
			isValidName = false;
		});
	};

	$scope.submit = function() {
		$scope.IDError = "";
		$scope.SSNError = "";
		
		if (isValidName == false) {
			$scope.distName="";
			$scope.IDError = "입력하신 회원정보를 찾을 수 없습니다. 회원번호를 다시 확인해 주십시오.";
			return;
		} else {

			var isValidSSN = $scope.validateSSN($scope.ssn1, $scope.ssn2);

			if (isValidSSN == false) {
				$scope.SSNError = "주민등록번호를 다시 확인해 주십시오.";
				return;
			} else
			{	
				var userData = {
      				'distID' : $scope.distID,
      				'ssn' : $scope.ssn1+$scope.ssn2
      			};
      		
      			userData = JSON.stringify(userData);
				
				var request = $http({
					method: "POST",
					url: "ssnSaver.php",
					data: userData,
					headers: {'Content-Type': 'application/x-www-form-urlencoded' }
				});

				request.success(function(response) {
        			alert('주민번호 입력이 완료 되었습니다.');
        			$scope.resetAll();
        			location.replace('http://www.makelifebetter.co.kr');
					        			
    			}).error(function(msg, code) {
    				//console.log(msg);
        			
        		});

			}
		}

	};

	$scope.validateSSN = function(ssn1, ssn2) {

		if (ssn1 === "" || ssn2 === "" || $scope.isNumericValue(ssn1) == false || $scope.isNumericValue(ssn2) == false || ssn1.length != 6 || ssn2.length != 7) {
			return false;
		} else {
			var ssn = ssn1.toString()+ssn2.toString();
			var multiplyer = [2,3,4,5,6,7,8,9,2,3,4,5];
			var arrSSN = [];
			 			
			angular.forEach(ssn, function(value,key){
				arrSSN.push(value);
			});
			 
			var sum = 0;
			for(var i=0; i<12; i++) {
				sum += multiplyer[i]*=arrSSN[i];
			}
			//console.log(sum);
			
			var value = (11-(sum%11))%10;
					
			if (arrSSN[6] == 5 || arrSSN[6] == 6 || arrSSN[6] == 7 || arrSSN[6] == 8) // for Foreigners, need extra calculation
			{
				if (value >= 10)
					value -= 10;
				
				value += 2;

				if (value >= 10)
					value -= 10;
			} 
			//console.log(value);
			//console.log(arrSSN[12]);
			return (value == arrSSN[12]); 
		}
	};
	$scope.isNumericValue = function(num){
		var reg = /^\d+$/;
		return reg.test(num);
	};
	$scope.resetAll = function(){
		$scope.IDError = "";
		$scope.SSNError = "";
		$scope.distName="";
		$scope.distID="";
		$scope.ssn1="";
		$scope.ssn2="";
	};

	 
});