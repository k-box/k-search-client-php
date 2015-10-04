<?php

/**
* Test the KlinkHelpers Class for basic functionality
*/
class KlinkHelpersTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{

	  	date_default_timezone_set('America/Los_Angeles');

	}


	public function phoneNumbersInput_INVALID()
	{
		return array(
			array('mob 07777 777777'),
			array('1234 567 890 after 5pm'),
			array('john smith'),
			array('(empty)'),
			array("1/234/567/8901"),
			array("not a phone number"),
			array('http://base'),
			array(''),
			array(null),
			array('iusbdnjudsudu@sidud'),
			array('a-sds.com'),
			array('1-234-567-8901 ext1234'),
			array('1-234 567.89/01 ext.1234'),
		);
	}

	public function phoneNumbersInput_VALID()
	{
		return array(

			array('(+351) 282 43 50 50'),
			array('90191919908'),
			array("1.234.567.8901"),
			array('555-8909'),
			array('001 6867684'),
			array('001 6867684x1'),
			array('1 (234) 567-8901'),
			array('1-234-567-8901 x1234'),
			array('1(234)5678901x1234'),
			array('(123)8575973'),
			array('(0055)(123)8575973'),
			array('02-64487851'),
			array('+39 02-64487851'),
			array("1-234-567-8901"),
			array("1-234-567-8901 x1234"),
			array("1 (234) 567-8901"),
			array('12 1234 123 1 x1111'),
			array('12 12 12 12 12'),
			array('12 1 1234 123456 x12345'),
			array('+12 1234 1234'),
			array('+12 12 12 1234'),
			array('+12 1234 5678'),
			array('+12 12345678'),

		);
	}

	public function mailInputs_INVALID(){
		return array(
		  array('http://base'),
		  array(''),
		  array(null),
		  array('iusbdnjudsudu@sidud'),
		  array('a-sds.com'),
		);
	}

	public function mailInputs_VALID(){
		return array(
		  array('io@me.com'),
		  array('io.dudua@meiubyd.com'),
		  array('io-778@me.com'),
		);
	}

	public function sanitize_inputs(){
		return array(
		  array('io@me.com', 'io@me.com'),
		  array('http://ciao.com/', 'http://ciao.com/'),
		  array('#bada55', '#bada55'),
		  array('<b>string with html</b>', 'string with html'),
		  array('"quotes"', '&#34;quotes&#34;'),
		  array('<script>alert(\'\');</script>Is there a script?', 'alert(&#39;&#39;);Is there a script?'),
		);
	}


	public function invalid_url()
	{
		return array(
		  array(null),
		  array('null'),
		  array(''),
		  array(' '),
		  array(0),
		  array(':/ciao.pinco'),
		  array('//www.example.co'),
		  array('ciao'),
		  array('s'),
		  array('http://'),
		  array('http://.'),
		  array('http://..'),
		  array('http://../'),
		  array('http://?'),
		  array('http://??'),
		  array('http://??/'),
		  array('http://#'),
		  array('http://##'),
		  array('http://##/'),
		  array('http://foo.bar?q=Spaces should be encoded'),
		  array('//'),
		  array('//a'),
		  array('///a'),
		  array('///'),
		  array('http:///a'),
		  array('foo.com'),
		  array('rdar://1234'),
		  array('h://test'),
		  array('http:// shouldfail.com'),
		  array(':// should fail'),
		  array('http://foo.bar/foo(bar)baz quux'),
		  array('ftps://foo.bar/'),
		  array('http://-error-.invalid/'),
		  array('http://-a.b.co'),
		  array('http://a.b-.co'),
		  array('http://0.0.0.0'),
		  array('http://10.1.1.0'),
		  array('http://10.1.1.255'),
		  array('http://224.1.1.1'),
		  array('http://1.1.1.1.1'),
		  array('http://123.123.123'),
		  array('http://3628126748'),
		  array('http://.www.foo.bar/'),
		  array('http://www.foo.bar./'),
		  array('http://.www.foo.bar./'),
		  array('http://10.1.1.1'),
		  array('http//www.example.co'),
		  array('http//example.co'),
		  array('http//git.io/'),
		  array('http//t.co/'),
		);
	}

	public function valid_url()
	{
		return array(
		  array('https://ciao.pinco'),
		  array('http://www.example.co'),
		  array('http://example.co'),
		  array('http://git.io/'),
		  array('http://t.co/'),
		  array('http://localhost/'),
		  array('http://127.0.0.1/'),
		  array('http://192.168.0.7/kcore/'),
		  array('https://212.42.104.135/kcore/'),
		  array('http://foo.com/blah_blah'),
		  array('http://foo.com/blah_blah/'),
		  array('http://foo.com/blah_blah_(wikipedia)'),
		  array('http://foo.com/blah_blah_(wikipedia)_(again)'),
		  array('http://www.example.com/wpstyle/?p=364'),
		  array('https://www.example.com/foo/?bar=baz&inga=42&quux'),
		  array('http://✪df.ws/123'),
		  array('http://userid:password@example.com:8080'),
		  array('http://userid:password@example.com:8080/'),
		  array('http://userid@example.com'),
		  array('http://userid@example.com/'),
		  array('http://userid@example.com:8080'),
		  array('http://userid@example.com:8080/'),
		  array('http://userid:password@example.com'),
		  array('http://userid:password@example.com/'),
		  array('http://142.42.1.1/'),
		  array('http://142.42.1.1:8080/'),
		  array('http://➡.ws/䨹'),
		  array('http://⌘.ws'),
		  array('http://⌘.ws/'),
		  array('http://foo.com/blah_(wikipedia)#cite-1'),
		  array('http://foo.com/blah_(wikipedia)_blah#cite-1'),
		  array('http://foo.com/unicode_(✪)_in_parens'),
		  array('http://foo.com/(something)?after=parens'),
		  array('http://☺.damowmow.com/'),
		  array('http://code.google.com/events/#&product=browser'),
		  array('http://j.mp'),
		  array('ftp://foo.bar/baz'),
		  array('http://foo.bar/?q=Test%20URL-encoded%20stuff'),
		  array('http://مثال.إختبار'),
		  array('http://例子.测试'),
		  array('http://उदाहरण.परीक्षा'),
		  array('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'),
		  array('http://1337.net'),
		  array('http://a.b-c.de'),
		  array('http://223.255.255.254'),
		  array('http://172.17.42.1'),
		  array('https://172.17.42.1'),
		);
	}


	public function camel_case_to_unserscore()
	{
		return array(
		  array('camelCase', 'camel_case'),
		  array('StartWithACamel', 'start_with_a_camel'),
		);
	}


	public function invalid_groups()
	{
		return array(
		  array(array()),
		  array('null'),
		  array(null),
		  array(true),
		  array(false),
		  array('-10:ciao'),
		  array('-10:-19'),
		  array('0:ciao'),
		  array('0:.10'),
		  array('0:-5'),
		  array('ciao:5'),
		);
	}

	public function valid_groups()
	{
		return array(
		  array('0:1'),
		  array('0:110'),
		  array('12:1'),
		  array('100:11029'),
		);
	}
	
	public function valid_strings(){
		return array(
			array('ciao'),
			array('c'),
			array('0'),
			array('01'),
			array('true'),
			array('false'),
			array('null'),
			array('undefined'),
		);
	}
	
	public function invalid_strings(){
		return array(
			array(''),	
			array(array()),
			array(new stdClass()),
			array(null),
			array(false),
			array(true),
			array(5),
			array(5.0),
				
		);
	}
	
	
	public function naughty_strings(){
		
		// from https://github.com/minimaxir/big-list-of-naughty-strings
		// srtings are base64 encoded to overcome character encoding

		return array(
array("dW5kZWZpbmVkCg=="),
array("dW5kZWYK"),
array("bnVsbAo="),
array("TlVMTAo="),
array("KG51bGwpCg=="),
array("bmlsCg=="),
array("TklMCg=="),
array("dHJ1ZQo="),
array("ZmFsc2UK"),
array("VHJ1ZQo="),
array("RmFsc2UK"),
array("Tm9uZQo="),
array("XFw="),
array("MAo="),
array("XFxcXAo="),
array("MQo="),
array("MS4wMAo="),
array("JDEuMDAK"),
array("MS8yCg=="),
array("MUUyCg=="),
array("MUUwMgo="),
array("MUUrMDIK"),
array("LTEK"),
array("LTEuMDAK"),
array("LSQxLjAwCg=="),
array("LTEvMgo="),
array("LTFFMgo="),
array("LTFFMDIK"),
array("LTFFKzAyCg=="),
array("MS8wCg=="),
array("MC8wCg=="),
array("LTIxNDc0ODM2NDgvLTEK"),
array("LTkyMjMzNzIwMzY4NTQ3NzU4MDgvLTEK"),
array("MC4wMAo="),
array("MC4uMAo="),
array("Lgo="),
array("MC4wLjAK"),
array("MCwwMAo="),
array("MCwsMAo="),
array("LAo="),
array("MCwwLDAK"),
array("MC4wLzAK"),
array("MS4wLzAuMAo="),
array("MC4wLzAuMAo="),
array("MSwwLzAsMAo="),
array("MCwwLzAsMAo="),
array("LS0xCg=="),
array("LQo="),
array("LS4K"),
array("LSwK"),
array("OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5Cg=="),
array("TmFOCg=="),
array("SW5maW5pdHkK"),
array("LUluZmluaXR5Cg=="),
array("MHgwCg=="),
array("MHhmZmZmZmZmZgo="),
array("MHhmZmZmZmZmZmZmZmZmZmZmCg=="),
array("MHhhYmFkMWRlYQo="),
array("MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTIzNDU2Nzg5Cg=="),
array("MSwwMDAuMDAK"),
array("MSAwMDAuMDAK"),
array("MScwMDAuMDAK"),
array("MSwwMDAsMDAwLjAwCg=="),
array("MSAwMDAgMDAwLjAwCg=="),
array("MScwMDAnMDAwLjAwCg=="),
array("MS4wMDAsMDAK"),
array("MSAwMDAsMDAK"),
array("MScwMDAsMDAK"),
array("MS4wMDAuMDAwLDAwCg=="),
array("MSAwMDAgMDAwLDAwCg=="),
array("MScwMDAnMDAwLDAwCg=="),
array("MDEwMDAK"),
array("MDgK"),
array("MDkK"),
array("Mi4yMjUwNzM4NTg1MDcyMDExZS0zMDgK"),
array("LC4vOydbXS09Cg=="),
array("PD4/OiJ7fXxfKwo="),
array("IUAjJCVeJiooKWB+Cg=="),
array("zqniiYjDp+KImuKIq8ucwrXiiaTiiaXDtwo="),
array("w6XDn+KIgsaSwqnLmeKIhsuawqzigKbDpgo="),
array("xZPiiJHCtMKu4oCgwqXCqMuGw7jPgOKAnOKAmAo="),
array("wqHihKLCo8Ki4oiewqfCtuKAosKqwrrigJPiiaAK"),
array("wrjLm8OH4peKxLHLnMOCwq/LmMK/Cg=="),
array("w4XDjcOOw4/LncOTw5Tvo7/DksOaw4bimIMK"),
array("xZLigJ7CtOKAsMuHw4HCqMuGw5jiiI/igJ3igJkK"),
array("YOKBhOKCrOKAueKAuu+sge+sguKAocKwwrfigJrigJTCsQo="),
array("4oWb4oWc4oWd4oWeCg=="),
array("0IHQgtCD0ITQhdCG0IfQiNCJ0IrQi9CM0I3QjtCP0JDQkdCS0JPQlNCV0JbQl9CY0JnQmtCb0JzQndCe0J/QoNCh0KLQo9Ck0KXQptCn0KjQqdCq0KvQrNCt0K7Qr9Cw0LHQstCz0LTQtdC20LfQuNC50LrQu9C80L3QvtC/0YDRgdGC0YPRhNGF0YbRh9GI0YnRitGL0YzRjdGO0Y8K"),
array("2aDZodmi2aPZpNml2abZp9mo2akK"),
array("4oGw4oG04oG1Cg=="),
array("4oKA4oKB4oKCCg=="),
array("4oGw4oG04oG14oKA4oKB4oKCCg=="),
array("Jwo="),
array("Igo="),
array("JycK"),
array("IiIK"),
array("JyInCg=="),
array("IicnJyciJyIK"),
array("IiciJyInJycnIgo="),
array("55Sw5Lit44GV44KT44Gr44GC44GS44Gm5LiL44GV44GECg=="),
array("44OR44O844OG44Kj44O844G46KGM44GL44Gq44GE44GLCg=="),
array("5ZKM6KO95ryi6KqeCg=="),
array("6YOo6JC95qC8Cg=="),
array("7IKs7ZqM6rO87ZWZ7JuQIOyWtO2VmeyXsOq1rOyGjAo="),
array("7LCm7LCo66W8IO2DgOqzoCDsmKgg7Y6y7Iuc66eo6rO8IOyRm+uLpOumrCDrmKDrsKnqsIHtlZgK"),
array("56S+5pyD56eR5a246Zmi6Kqe5a2456CU56m25omACg=="),
array("7Jq4656A67CU7Yag66W0Cg=="),
array("8KCcjvCgnLHwoJ258KCxk/CgsbjwoLKW8KCzjwo="),
array("44O94Ly84LqI2YTNnOC6iOC8ve++iSDjg73gvLzguojZhM2c4LqI4Ly9776JCg=="),
array("KO+9oeKXlSDiiIAg4peV772hKQo="),
array("772A772oKMK04oiA772A4oipCg=="),
array("X1/vvpsoLF8sKikK"),
array("44O7KO+/o+KIgO+/oynjg7s6KjoK"),
array("776f772l4py/44O+4pWyKO+9oeKXleKAv+KXle+9oSnilbHinL/vvaXvvp8K"),
array("LOOAguODuzoqOuODu+OCnOKAmSgg4pi7IM+JIOKYuyAp44CC44O7Oio644O744Kc4oCZCg=="),
array("KOKVr8Kw4pahwrDvvInila/vuLUg4pS74pSB4pS7KQo="),
array("KO++ieCypeebiuCype+8ie++ie+7vyDilLvilIHilLsK"),
array("KCDNocKwIM2cypYgzaHCsCkK"),
array("8J+YjQo="),
array("8J+RqfCfj70K"),
array("8J+RviDwn5mHIPCfkoEg8J+ZhSDwn5mGIPCfmYsg8J+ZjiDwn5mNCg=="),
array("8J+QtSDwn5mIIPCfmYkg8J+Zigo="),
array("4p2k77iPIPCfkpQg8J+SjCDwn5KVIPCfkp4g8J+SkyDwn5KXIPCfkpYg8J+SmCDwn5KdIPCfkp8g8J+SnCDwn5KbIPCfkpog8J+SmQo="),
array("4pyL8J+PvyDwn5Kq8J+PvyDwn5GQ8J+PvyDwn5mM8J+PvyDwn5GP8J+PvyDwn5mP8J+Pvwo="),
array("8J+aviDwn4aSIPCfhpMg8J+GlSDwn4aWIPCfhpcg8J+GmSDwn4+nCg=="),
array("MO+4j+KDoyAx77iP4oOjIDLvuI/ig6MgM++4j+KDoyA077iP4oOjIDXvuI/ig6MgNu+4j+KDoyA377iP4oOjIDjvuI/ig6MgOe+4j+KDoyDwn5SfCg=="),
array("77yR77yS77yTCg=="),
array("2aHZotmjCg=="),
array("15HWsNa816jWtdeQ16nWtNeB15nXqiwg15HWuNa816jWuNeQINeQ1rHXnNa515TWtNeZ150sINeQ1rXXqiDXlNa316nWuNa814HXnta315nWtNedLCDXldaw15DWtdeqINeU1rjXkNa416jWttelCg=="),
array("15TWuNeZ1rDXqta415R0ZXN02KfZhNi12YHYrdin2Kog2KfZhNiq2ZHYrdmI2YQK"),
array("77e9Cg=="),
array("77e6Cg=="),
array("4oCLCg=="),
array("4ZqACg=="),
array("4aCOCg=="),
array("44CACg=="),
array("77u/Cg=="),
array("4pCjCg=="),
array("4pCiCg=="),
array("4pChCg=="),
array("4oCq4oCqdGVzdOKAqgo="),
array("4oCrdGVzdOKAqwo="),
array("4oCpdGVzdOKAqQo="),
array("dGVzdOKBoHRlc3TigKsK"),
array("4oGmdGVzdOKBpwo="),
array("WsyuzJ7MoM2ZzZTNheG4gMyXzJ7NiMy7zJfhuLbNmc2OzK/MucyezZNHzLtPzK3Ml8yuCg=="),
array("y5nJkG5i4bSJbMmQIMmQdcaDyZDJryDHncm5b2xvcCDKh8edIMedyblvccmQbCDKh24gyod1bnDhtIlw4bSJyZR14bSJIMm5b2TJr8edyocgcG/Jr3Nu4bSJx50gb3AgcMedcyAnyofhtIlsx50gxoN14bSJyZRz4bSJZOG0iXDJkCDJuW7Kh8edyofJlMedc3VvyZQgJ8qHx53Jr8mQIMqH4bSJcyDJuW9sb3Agya9uc2ThtIkgya/Hncm5b8ulCg=="),
array("MDDLmcaWJC0K"),
array("77y0772I772FIO+9ke+9le+9ie+9g++9iyDvvYLvvZLvvY/vvZfvvY4g772G772P772YIO+9iu+9le+9je+9kO+9kyDvvY/vvZbvvYXvvZIg772U772I772FIO+9jO+9ge+9mu+9mSDvvYTvvY/vvYcK"),
array("8J2Qk/CdkKHwnZCeIPCdkKrwnZCu8J2QovCdkJzwnZCkIPCdkJvwnZCr8J2QqPCdkLDwnZCnIPCdkJ/wnZCo8J2QsSDwnZCj8J2QrvCdkKbwnZCp8J2QrCDwnZCo8J2Qr/CdkJ7wnZCrIPCdkK3wnZCh8J2QniDwnZCl8J2QmvCdkLPwnZCyIPCdkJ3wnZCo8J2QoAo="),
array("8J2Vv/Cdlo3wnZaKIPCdlpbwnZaa8J2WjvCdlojwnZaQIPCdlofwnZaX8J2WlPCdlpzwnZaTIPCdlovwnZaU8J2WnSDwnZaP8J2WmvCdlpLwnZaV8J2WmCDwnZaU8J2Wm/CdlorwnZaXIPCdlpnwnZaN8J2WiiDwnZaR8J2WhvCdlp/wnZaeIPCdlonwnZaU8J2WjAo="),
array("8J2Ru/CdkonwnZKGIPCdkpLwnZKW8J2SivCdkoTwnZKMIPCdkoPwnZKT8J2SkPCdkpjwnZKPIPCdkofwnZKQ8J2SmSDwnZKL8J2SlvCdko7wnZKR8J2SlCDwnZKQ8J2Sl/CdkobwnZKTIPCdkpXwnZKJ8J2ShiDwnZKN8J2SgvCdkpvwnZKaIPCdkoXwnZKQ8J2SiAo="),
array("8J2To/Cdk7HwnZOuIPCdk7rwnZO+8J2TsvCdk6zwnZO0IPCdk6vwnZO78J2TuPCdlIDwnZO3IPCdk6/wnZO48J2UgSDwnZOz8J2TvvCdk7bwnZO58J2TvCDwnZO48J2Tv/Cdk67wnZO7IPCdk73wnZOx8J2TriDwnZO18J2TqvCdlIPwnZSCIPCdk63wnZO48J2TsAo="),
array("8J2Vi/CdlZnwnZWWIPCdlaLwnZWm8J2VmvCdlZTwnZWcIPCdlZPwnZWj8J2VoPCdlajwnZWfIPCdlZfwnZWg8J2VqSDwnZWb8J2VpvCdlZ7wnZWh8J2VpCDwnZWg8J2Vp/CdlZbwnZWjIPCdlaXwnZWZ8J2VliDwnZWd8J2VkvCdlavwnZWqIPCdlZXwnZWg8J2VmAo="),
array("8J2ag/CdmpHwnZqOIPCdmprwnZqe8J2akvCdmozwnZqUIPCdmovwnZqb8J2amPCdmqDwnZqXIPCdmo/wnZqY8J2aoSDwnZqT8J2anvCdmpbwnZqZ8J2anCDwnZqY8J2an/Cdmo7wnZqbIPCdmp3wnZqR8J2ajiDwnZqV8J2aivCdmqPwnZqiIPCdmo3wnZqY8J2akAo="),
array("4pKv4pKj4pKgIOKSrOKSsOKSpOKSnuKSpiDikp3ikq3ikqrikrLikqkg4pKh4pKq4pKzIOKSpeKSsOKSqOKSq+KSriDikqrikrHikqDikq0g4pKv4pKj4pKgIOKSp+KSnOKSteKStCDikp/ikqrikqIK"),
array("PHNjcmlwdD5hbGVydCgxMjMpPC9zY3JpcHQ+Cg=="),
array("Jmx0O3NjcmlwdCZndDthbGVydCgmIzM5OzEyMyYjMzk7KTsmbHQ7L3NjcmlwdCZndDsK"),
array("PGltZyBzcmM9eCBvbmVycm9yPWFsZXJ0KDEyMykgLz4K"),
array("PHN2Zz48c2NyaXB0PjEyMzwxPmFsZXJ0KDEyMyk8L3NjcmlwdD4K"),
array("Ij48c2NyaXB0PmFsZXJ0KDEyMyk8L3NjcmlwdD4K"),
array("Jz48c2NyaXB0PmFsZXJ0KDEyMyk8L3NjcmlwdD4K"),
array("PjxzY3JpcHQ+YWxlcnQoMTIzKTwvc2NyaXB0Pgo="),
array("PC9zY3JpcHQ+PHNjcmlwdD5hbGVydCgxMjMpPC9zY3JpcHQ+Cg=="),
array("PCAvIHNjcmlwdCA+PCBzY3JpcHQgPmFsZXJ0KDEyMyk8IC8gc2NyaXB0ID4K"),
array("b25mb2N1cz1KYVZhU0NyaXB0OmFsZXJ0KDEyMykgYXV0b2ZvY3VzCg=="),
array("IiBvbmZvY3VzPUphVmFTQ3JpcHQ6YWxlcnQoMTIzKSBhdXRvZm9jdXMK"),
array("JyBvbmZvY3VzPUphVmFTQ3JpcHQ6YWxlcnQoMTIzKSBhdXRvZm9jdXMK"),
array("77ycc2NyaXB077yeYWxlcnQoMTIzKe+8nC9zY3JpcHTvvJ4K"),
array("PHNjPHNjcmlwdD5yaXB0PmFsZXJ0KDEyMyk8L3NjPC9zY3JpcHQ+cmlwdD4K"),
array("LS0+PHNjcmlwdD5hbGVydCgxMjMpPC9zY3JpcHQ+Cg=="),
array("IjthbGVydCgxMjMpO3Q9Igo="),
array("JzthbGVydCgxMjMpO3Q9Jwo="),
array("SmF2YVNDcmlwdDphbGVydCgxMjMpCg=="),
array("O2FsZXJ0KDEyMyk7Cg=="),
array("c3JjPUphVmFTQ3JpcHQ6cHJvbXB0KDEzMikK"),
array("Ij48c2NyaXB0PmFsZXJ0KDEyMyk7PC9zY3JpcHQgeD0iCg=="),
array("Jz48c2NyaXB0PmFsZXJ0KDEyMyk7PC9zY3JpcHQgeD0nCg=="),
array("PjxzY3JpcHQ+YWxlcnQoMTIzKTs8L3NjcmlwdCB4PQo="),
array("IiBhdXRvZm9jdXMgb25rZXl1cD0iamF2YXNjcmlwdDphbGVydCgxMjMpCg=="),
array("JyBhdXRvZm9jdXMgb25rZXl1cD0namF2YXNjcmlwdDphbGVydCgxMjMpCg=="),
array("PHNjcmlwdHgyMHR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgzRXR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgwRHR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgwOXR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgwQ3R5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgyRnR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("PHNjcmlwdHgwQXR5cGU9InRleHQvamF2YXNjcmlwdCI+amF2YXNjcmlwdDphbGVydCgxKTs8L3NjcmlwdD4K"),
array("J2AiPjx4M0NzY3JpcHQ+amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("J2AiPjx4MDBzY3JpcHQ+amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("QUJDPGRpdiBzdHlsZT0ieHgzQWV4cHJlc3Npb24oamF2YXNjcmlwdDphbGVydCgxKSI+REVGCg=="),
array("QUJDPGRpdiBzdHlsZT0ieDpleHByZXNzaW9ueDVDKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDpleHByZXNzaW9ueDAwKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDpleHB4MDByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDpleHB4NUNyZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MEFleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MDlleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTN4ODB4ODBleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODRleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4QzJ4QTBleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODBleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4OEFleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MERleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MENleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODdleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RUZ4QkJ4QkZleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MjBleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODhleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MDBleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4OEJleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODZleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODVleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODJleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4MEJleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODFleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODNleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("QUJDPGRpdiBzdHlsZT0ieDp4RTJ4ODB4ODlleHByZXNzaW9uKGphdmFzY3JpcHQ6YWxlcnQoMSkiPkRFRgo="),
array("PGEgaHJlZj0ieDBCamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDBGamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEMyeEEwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA1amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUxeEEweDhFamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE4amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDExamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg4amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg5amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDgwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE3amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDAzamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDBFamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFBamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDAwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDEwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDgyamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDIwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDEzamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA5amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDhBamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE0amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE5amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweEFGamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFGamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDgxamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFEamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg3amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA3amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUxeDlBeDgwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDgzamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA0amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDAxamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA4amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg0amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg2amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUzeDgweDgwamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDEyamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDBEamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDBBamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDBDamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE1amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweEE4amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDE2amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDAyamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFCamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDA2amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweEE5amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgweDg1amF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFFamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieEUyeDgxeDlGamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0ieDFDamF2YXNjcmlwdDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0iamF2YXNjcmlwdHgwMDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0iamF2YXNjcmlwdHgzQTpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0iamF2YXNjcmlwdHgwOTpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0iamF2YXNjcmlwdHgwRDpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("PGEgaHJlZj0iamF2YXNjcmlwdHgwQTpqYXZhc2NyaXB0OmFsZXJ0KDEpIiBpZD0iZnV6emVsZW1lbnQxIj50ZXN0PC9hPgo="),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwQW9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgyMm9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwQm9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwRG9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgyRm9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwOW9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwQ29uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgwMG9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgyN29uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("YCInPjxpbWcgc3JjPXh4eDp4IHgyMG9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT4K"),
array("ImAnPjxzY3JpcHQ+eDNCamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDBEamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEVGeEJCeEJGamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDgxamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg0amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUzeDgweDgwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDA5amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg5amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg1amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg4amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDAwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweEE4amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDhBamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUxeDlBeDgwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDBDamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDJCamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEYweDkweDk2eDlBamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+LWphdmFzY3JpcHQ6YWxlcnQoMSk8L3NjcmlwdD4K"),
array("ImAnPjxzY3JpcHQ+eDBBamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweEFGamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDdFamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg3amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgxeDlGamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweEE5amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEMyeDg1amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEVGeEJGeEFFamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDgzamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDhCamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEVGeEJGeEJFamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDgwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDIxamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDgyamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUyeDgweDg2amF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEUxeEEweDhFamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDBCamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eDIwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("ImAnPjxzY3JpcHQ+eEMyeEEwamF2YXNjcmlwdDphbGVydCgxKTwvc2NyaXB0Pgo="),
array("PGltZyB4MDBzcmM9eCBvbmVycm9yPSJhbGVydCgxKSI+Cg=="),
array("PGltZyB4NDdzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyB4MTFzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyB4MTJzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZ3g0N3NyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ3gxMHNyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ3gxM3NyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ3gzMnNyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ3g0N3NyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ3gxMXNyYz14IG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZyB4NDdzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyB4MzRzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyB4MzlzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyB4MDBzcmM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MDk9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MTA9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MTM9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MzI9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MTI9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MTE9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4MDA9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmN4NDc9eCBvbmVycm9yPSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eHgwOW9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZyBzcmM9eHgxMG9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZyBzcmM9eHgxMW9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZyBzcmM9eHgxMm9uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZyBzcmM9eHgxM29uZXJyb3I9ImphdmFzY3JpcHQ6YWxlcnQoMSkiPgo="),
array("PGltZ1thXVtiXVtjXXNyY1tkXT14W2Vdb25lcnJvcj1bZl0iYWxlcnQoMSkiPgo="),
array("PGltZyBzcmM9eCBvbmVycm9yPXgwOSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eCBvbmVycm9yPXgxMCJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eCBvbmVycm9yPXgxMSJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eCBvbmVycm9yPXgxMiJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eCBvbmVycm9yPXgzMiJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGltZyBzcmM9eCBvbmVycm9yPXgwMCJqYXZhc2NyaXB0OmFsZXJ0KDEpIj4K"),
array("PGEgaHJlZj1qYXZhJiMxJiMyJiMzJiM0JiM1JiM2JiM3JiM4JiMxMSYjMTJzY3JpcHQ6amF2YXNjcmlwdDphbGVydCgxKT5YWFg8L2E+Cg=="),
array("PGltZyBzcmM9InhgIGA8c2NyaXB0PmphdmFzY3JpcHQ6YWxlcnQoMSk8L3NjcmlwdD4iYCBgPgo="),
array("PGltZyBzcmMgb25lcnJvciAvIiAnIj0gYWx0PWphdmFzY3JpcHQ6YWxlcnQoMSkvLyI+Cg=="),
array("PHRpdGxlIG9ucHJvcGVydHljaGFuZ2U9amF2YXNjcmlwdDphbGVydCgxKT48L3RpdGxlPjx0aXRsZSB0aXRsZT0+Cg=="),
array("PGEgaHJlZj1odHRwOi8vZm9vLmJhci8jeD1geT48L2E+PGltZyBhbHQ9ImA+PGltZyBzcmM9eDp4IG9uZXJyb3I9amF2YXNjcmlwdDphbGVydCgxKT48L2E+Ij4K"),
array("PCEtLVtpZl0+PHNjcmlwdD5qYXZhc2NyaXB0OmFsZXJ0KDEpPC9zY3JpcHQgLS0+Cg=="),
array("PCEtLVtpZjxpbWcgc3JjPXggb25lcnJvcj1qYXZhc2NyaXB0OmFsZXJ0KDEpLy9dPiAtLT4K"),
array("PHNjcmlwdCBzcmM9Ii8lKGpzY3JpcHQpcyI+PC9zY3JpcHQ+Cg=="),
array("PHNjcmlwdCBzcmM9IlwlKGpzY3JpcHQpcyI+PC9zY3JpcHQ+Cg=="),
array("PElNRyAiIiI+PFNDUklQVD5hbGVydCgiWFNTIik8L1NDUklQVD4iPgo="),
array("PElNRyBTUkM9amF2YXNjcmlwdDphbGVydChTdHJpbmcuZnJvbUNoYXJDb2RlKDg4LDgzLDgzKSk+Cg=="),
array("PElNRyBTUkM9IyBvbm1vdXNlb3Zlcj0iYWxlcnQoJ3h4cycpIj4K"),
array("PElNRyBTUkM9IG9ubW91c2VvdmVyPSJhbGVydCgneHhzJykiPgo="),
array("PElNRyBvbm1vdXNlb3Zlcj0iYWxlcnQoJ3h4cycpIj4K"),
array("PElNRyBTUkM9JiN4NkEmI3g2MSYjeDc2JiN4NjEmI3g3MyYjeDYzJiN4NzImI3g2OSYjeDcwJiN4NzQmI3gzQSYjeDYxJiN4NkMmI3g2NSYjeDcyJiN4NzQmI3gyOCYjeDI3JiN4NTgmI3g1MyYjeDUzJiN4MjcmI3gyOT4K"),
array("PElNRyBTUkM9ImphdiBhc2NyaXB0OmFsZXJ0KCdYU1MnKTsiPgo="),
array("PElNRyBTUkM9ImphdiYjeDA5O2FzY3JpcHQ6YWxlcnQoJ1hTUycpOyI+Cg=="),
array("PElNRyBTUkM9ImphdiYjeDBBO2FzY3JpcHQ6YWxlcnQoJ1hTUycpOyI+Cg=="),
array("PElNRyBTUkM9ImphdiYjeDBEO2FzY3JpcHQ6YWxlcnQoJ1hTUycpOyI+Cg=="),
array("cGVybCAtZSAncHJpbnQgIjxJTUcgU1JDPWphdmEwc2NyaXB0OmFsZXJ0KCJYU1MiKT4iOycgPiBvdXQK"),
array("PElNRyBTUkM9IiAmIzE0OyBqYXZhc2NyaXB0OmFsZXJ0KCdYU1MnKTsiPgo="),
array("PFNDUklQVC9YU1MgU1JDPSJodHRwOi8vaGEuY2tlcnMub3JnL3hzcy5qcyI+PC9TQ1JJUFQ+Cg=="),
array("PEJPRFkgb25sb2FkISMkJSYoKSp+Ky1fLiw6Oz9AWy98XV5gPWFsZXJ0KCJYU1MiKT4K"),
array("PFNDUklQVC9TUkM9Imh0dHA6Ly9oYS5ja2Vycy5vcmcveHNzLmpzIj48L1NDUklQVD4K"),
array("PDxTQ1JJUFQ+YWxlcnQoIlhTUyIpOy8vPDwvU0NSSVBUPgo="),
array("PFNDUklQVCBTUkM9aHR0cDovL2hhLmNrZXJzLm9yZy94c3MuanM/PCBCID4K"),
array("PFNDUklQVCBTUkM9Ly9oYS5ja2Vycy5vcmcvLmo+Cg=="),
array("PElNRyBTUkM9ImphdmFzY3JpcHQ6YWxlcnQoJ1hTUycpIgo="),
array("PGlmcmFtZSBzcmM9aHR0cDovL2hhLmNrZXJzLm9yZy9zY3JpcHRsZXQuaHRtbCA8Cg=="),
array("IjthbGVydCgnWFNTJyk7Ly8K"),
array("PHBsYWludGV4dD4K"),
array("MTtEUk9QIFRBQkxFIHVzZXJzCg=="),
array("MSc7IERST1AgVEFCTEUgdXNlcnMtLSAxCg=="),
array("JyBPUiAxPTEgLS0gMQo="),
array("JyBPUiAnMSc9JzEK"),
array("LQo="),
array("LS0K"),
array("LS12ZXJzaW9uCg=="),
array("LS1oZWxwCg=="),
array("JFVTRVIK"),
array("L2Rldi9udWxsOyB0b3VjaCAvdG1wL2JsbnMuZmFpbCA7IGVjaG8K"),
array("YHRvdWNoIC90bXAvYmxucy5mYWlsYAo="),
array("JCh0b3VjaCAvdG1wL2JsbnMuZmFpbCkK"),
array("QHtbc3lzdGVtICJ0b3VjaCAvdG1wL2JsbnMuZmFpbCJdfQo="),
array("ZXZhbCgicHV0cyAnaGVsbG8gd29ybGQnIikK"),
array("U3lzdGVtKCJscyAtYWwgLyIpCg=="),
array("YGxzIC1hbCAvYAo="),
array("S2VybmVsLmV4ZWMoImxzIC1hbCAvIikK"),
array("S2VybmVsLmV4aXQoMSkK"),
array("JXgoJ2xzIC1hbCAvJykK"),
array("PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iSVNPLTg4NTktMSI/PjwhRE9DVFlQRSBmb28gWyA8IUVMRU1FTlQgZm9vIEFOWSA+PCFFTlRJVFkgeHhlIFNZU1RFTSAiZmlsZTovLy9ldGMvcGFzc3dkIiA+XT48Zm9vPiZ4eGU7PC9mb28+Cg=="),
array("JEhPTUUK"),
array("JEVOVnsnSE9NRSd9Cg=="),
array("JWQK"),
array("JXMK"),
array("ezB9"),
array("JSouKnMK"),
array("Li4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vZXRjL3Bhc3N3ZCUwMAo="),
array("Li4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vZXRjL2hvc3RzCg=="),
array("KCkgeyAwOyB9OyB0b3VjaCAvdG1wL2JsbnMuc2hlbGxzaG9jazEuZmFpbDsK"),
array("KCkgeyBfOyB9ID5fWyQoJCgpKV0geyB0b3VjaCAvdG1wL2JsbnMuc2hlbGxzaG9jazIuZmFpbDsgfQo="),
array("Q09OCg=="),
array("UFJOCg=="),
array("QVVYCg=="),
array("Q0xPQ0skCg=="),
array("TlVMCg=="),
array("QToK"),
array("Wlo6Cg=="),
array("Q09NMQo="),
array("TFBUMQo="),
array("TFBUMgo="),
array("TFBUMwo="),
array("Q09NMgo="),
array("Q09NMwo="),
array("Q09NNAo="),
array("U2N1bnRob3JwZSBHZW5lcmFsIEhvc3BpdGFsCg=="),
array("UGVuaXN0b25lIENvbW11bml0eSBDaHVyY2gK"),
array("TGlnaHR3YXRlciBDb3VudHJ5IFBhcmsK"),
array("SmltbXkgQ2xpdGhlcm9lCg=="),
array("SG9ybmltYW4gTXVzZXVtCg=="),
array("c2hpdGFrZSBtdXNocm9vbXMK"),
array("Um9tYW5zSW5TdXNzZXguY28udWsK"),
array("aHR0cDovL3d3dy5jdW0ucWMuY2EvCg=="),
array("Q3JhaWcgQ29ja2J1cm4sIFNvZnR3YXJlIFNwZWNpYWxpc3QK"),
array("TGluZGEgQ2FsbGFoYW4K"),
array("RHIuIEhlcm1hbiBJLiBMaWJzaGl0ego="),
array("bWFnbmEgY3VtIGxhdWRlCg=="),
array("U3VwZXIgQm93bCBYWFgK"),
array("bWVkaWV2YWwgZXJlY3Rpb24gb2YgcGFyYXBldHMK"),
array("ZXZhbHVhdGUK"),
array("bW9jaGEK"),
array("ZXhwcmVzc2lvbgo="),
array("QXJzZW5hbCBjYW5hbAo="),
array("Y2xhc3NpYwo="),
array("VHlzb24gR2F5Cg=="),
array("SWYgeW91J3JlIHJlYWRpbmcgdGhpcywgeW91J3ZlIGJlZW4gaW4gYSBjb21hIGZvciBhbG1vc3QgMjAgeWVhcnMgbm93LiBXZSdyZSB0cnlpbmcgYSBuZXcgdGVjaG5pcXVlLiBXZSBkb24ndCBrbm93IHdoZXJlIHRoaXMgbWVzc2FnZSB3aWxsIGVuZCB1cCBpbiB5b3VyIGRyZWFtLCBidXQgd2UgaG9wZSBpdCB3b3Jrcy4gUGxlYXNlIHdha2UgdXAsIHdlIG1pc3MgeW91Lgo="),
array("Um9zZXMgYXJlIBtbMDszMW1yZWQbWzBtLCB2aW9sZXRzIGFyZSAbWzA7MzRtYmx1ZS4gSG9wZSB5b3UgZW5qb3kgdGVybWluYWwgaHVlCg=="),
array("QnV0IG5vdy4uLhtbMjBDZm9yIG15IGdyZWF0ZXN0IHRyaWNrLi4uG1s4bQo="),
array("VGhlIHF1aWMICAgICAhrIGJyb3duIGZvBwcHBwcHBwcHBwd4Li4uIFtCZWVlZXBdCg=="),
array("UG93ZXLZhNmP2YTZj9i12ZHYqNmP2YTZj9mE2LXZkdio2Y/Ysdix2Ysg4KWjIOClo2gg4KWjIOClo+WGlwo="),
		);
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}


	/**
	 * @dataProvider mailInputs_INVALID
	 * @expectedException InvalidArgumentException
	 */
	public function testIsMailValidWithInvalidData( $mail ){

		KlinkHelpers::is_valid_mail( $mail, "mail" );

	}

	/**
	 * @dataProvider mailInputs_VALID
	 */
	public function testIsMailValidWithValidData( $mail ){

		KlinkHelpers::is_valid_mail( $mail, "mail" );

		$this->assertTrue(true, 'invalid');

	}


	/**
	 * @dataProvider phoneNumbersInput_INVALID
	 * @expectedException InvalidArgumentException
	 */
	public function testIsPhoneValidWithInvalidData( $number ){

		KlinkHelpers::is_valid_phonenumber( $number, "number" );

	}

	/**
	 * @dataProvider invalid_url
	 * @expectedException InvalidArgumentException
	 */
	public function testUrlValidationWithInvalidData( $number ){

		KlinkHelpers::is_valid_url( $number, "url" );

	}

	/**
	 * @dataProvider valid_url
	 */
	public function testUrlValidationWithValidData( $number ){

		KlinkHelpers::is_valid_url( $number, "url" );

	}

	/**
	 * @dataProvider invalid_groups
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentGroupValidationWithInvalidData( $group ){

		KlinkHelpers::is_valid_document_group( $group );

	}

	/**
	 * @dataProvider valid_groups
	 */
	public function testDocumentGroupValidationWithValidData( $group ){

		KlinkHelpers::is_valid_document_group( $group );

	}


	/**
	 * @dataProvider phoneNumbersInput_VALID
	 */
	public function testIsPhoneValidWithValidData( $number ){

		KlinkHelpers::is_valid_phonenumber( $number, "number" );

		$this->assertTrue(true, 'invalid');

	}

	/**
	 * @dataProvider sanitize_inputs
	 */
	public function testSanitizeString( $string, $expected ){

		$sanitized = KlinkHelpers::sanitize_string( $string );


		$this->assertEquals($expected, $sanitized);
	}

	/**
	 * @dataProvider camel_case_to_unserscore
	 */
	public function testToUnderscoreCase($input, $expected)
	{
		$converted = KlinkHelpers::to_underscore_case($input);

		$this->assertEquals($expected, $converted);
	}
	
	/**
	 * @dataProvider valid_strings
	 */
	public function testStringValidationWithValidData( $value ){

		KlinkHelpers::is_string_and_not_empty( $value, "value " . var_export($value, true) );

	}

	/**
	 * @dataProvider invalid_strings
	 * @expectedException InvalidArgumentException
	 */
	public function testStringValidationWithInvalidData( $value ){

		KlinkHelpers::is_string_and_not_empty( $value, "value " . var_export($value, true) );

	}
	
	/**
	 * @dataProvider naughty_strings
	 */
	public function testStringValidationWithNaughtyData( $value ){

		KlinkHelpers::is_string_and_not_empty( base64_decode($value), "value " . var_export(base64_decode($value), true) );

	}

}