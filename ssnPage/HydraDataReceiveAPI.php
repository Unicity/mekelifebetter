<?
	$distID = $_POST['distID']!=''?$_POST['distID']:$_GET['distID'];
//echo ">>>".$distID."<br/>";
	$distID = trim($distID);

	$url = 'https://hydra.unicity.net/v5/customers?unicity='.$distID.'&expand=customer';
	$username = 'krWebEnrollment';
	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	
	$response = curl_exec($ch);
	 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	//echo $response;

	if (($response !== false) && ($status == 200)) {
		echo $response;
	} else {

		//$response = '{"items":[{"humanName":{"firstName":"Korea","lastName":"Unicity","fullName":"Korea Unicity","fullName@ko":"false"}}]}';
		//type이 D면 Associate 아니면 WholesaleCustomer

		//https://www.makelifebetter.co.kr/ssnPage/api.php?distID=216973682



		$response = '{"items":[{"depositBankAccount":"false","mainAddress":{"address1":"false","address2":"false","city":"false","state":"false","zip":"false","country":"KR"},"humanName":{"firstName":"Korea","lastName":"Unicity","fullName":"Korea Unicity","fullName@ko":"false"}, "status":"Active","type":"Associate"}]}';

	//{"items":[{"unicity":218142082,"depositBankAccount":null,"mainAddress":{"address1":"�곸꽌濡� 2918","address2":"112��604��","city":"異섏쿇��","state":"媛뺤썝","zip":"24224","country":"KR"},"humanName":{"firstName":"","lastName":"Jungyuenhee","fullName":"Jungyuenhee","fullName@ko":"�뺤뿰��"},"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c","id":{"unicity":"218142082"},"sponsoredCustomers":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/sponsoredCustomers"},"metricsProfile":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/metricsProfile"},"metricsProfileHistory":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/metricsProfileHistory"},"profilePicture":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/profilePicture"},"achievementsHistory":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/achievementsHistory"},"cumulativeMetricsProfile":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/cumulativeMetricsProfile"},"orders":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/orders"},"statements":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/statements"},"draftBankAccounts":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/draftBankAccounts"},"customerSite":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/customerSite"},"loginAssociations":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/loginassociations"},"birthDate":"1965-05-25","joinDate":"2019-12-02T12:00:00-06:00","status":"Active","type":"Associate","email":"1127jyh@hanmail.net","enroller":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"gender":"female","homePhone":"01059208834","mobilePhone":"01059208834","subscriptions":[{"type":"Franchise","startDate":"2019-12-02","endDate":"2020-12-02"}],"sponsor":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"taxTerms":{"taxId":"650525�뺤뿰��"},"businessEntity":null,"rights":[]}]}

	//	{"items":[{"unicity":218142082,"depositBankAccount":{"bankName":"false","bin":"false","accountHolder":"false","accountNumber":"false","accountType":"false","routingNumber":"false"},"mainAddress":{"address1":"�곸꽌濡� 2918","address2":"112��604��","city":"異섏쿇��","state":"媛뺤썝","zip":"24224","country":"KR"},"humanName":{"firstName":"","lastName":"Jungyuenhee","fullName":"Jungyuenhee","fullName@ko":"�뺤뿰��"},"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c","id":{"unicity":"218142082"},"sponsoredCustomers":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/sponsoredCustomers"},"metricsProfile":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/metricsProfile"},"metricsProfileHistory":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/metricsProfileHistory"},"profilePicture":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/profilePicture"},"achievementsHistory":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/achievementsHistory"},"cumulativeMetricsProfile":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/cumulativeMetricsProfile"},"orders":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/orders"},"statements":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/statements"},"draftBankAccounts":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/draftBankAccounts"},"customerSite":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/customerSite"},"loginAssociations":{"href":"https://hydra.unicity.net/v5/customers/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c/loginassociations"},"birthDate":"1965-05-25","joinDate":"2019-12-02T12:00:00-06:00","status":"Active","type":"Associate","email":"1127jyh@hanmail.net","enroller":{"href":"https://hydra.unicity.net/v5/customers/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"gender":"female","homePhone":"01059208834","mobilePhone":"01059208834","subscriptions":[{"type":"Franchise","startDate":"2019-12-02","endDate":"2020-12-02"}],"sponsor":{"href":"https://hydra.unicity.net/v5/customers/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"taxTerms":{"taxId":"650525�뺤뿰��"},"businessEntity":null,"rights":[]}]}


		echo $response;
	}

	curl_close($ch);

?>