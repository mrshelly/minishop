<?php
	$personInfo=array(
		'nickname'=>'俺的家家',
		'username'=>'mrshelly',
		'userid'=>150955,
		'recent'=>array(																//最近记录
			'visit'=>array(																//最近访问
				'toys'=>array(															//最近访问 toys
					0=>array(
						'id'=>2334,														//商品ID
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',			//商品图片
						'title'=>'aaaaaa',												//商品标题
						'ts'=>119234435,												//访问时间
					),
					1=>array(
						'id'=>2324,
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',
						'title'=>'aaaaaa',
						'ts'=>119234435,
					),
					2=>array(
						'id'=>24,
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',
						'title'=>'aaaaaa',
						'ts'=>119343245,
					),
					3=>array(
						'id'=>24334,
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',
						'title'=>'aaaaaa',
						'ts'=>119343245,
					),
				),
			),
			'comment'=>array(															//最近评论
				'toys'=>array(															//最近评论 toys
					0=>array(
						'id'=>2334,														//商品ID
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',			//商品图片
						'title'=>'aaaaaa',												//商品标题
						'ts'=>119234435,												//评论时间
					),
					1=>array(
						'id'=>24,
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',
						'title'=>'aaaaaa',
						'ts'=>119343245,
					),
					2=>array(
						'id'=>24,
						'pic'=>'http://pic_toys/image/1/2334_23435435435.jpg',
						'title'=>'aaaaaa',
						'ts'=>119343245,
					),
				),
			),
		),
	);
?>
<head>
<!-- CSS -->
<!-- JS -->
</head>
<body>
<div class="peopleMain">
	<div class="peopleNick">
		<span><a href="/people.php?mod=disp&disp=info&id=<?php echo $personInfo['userid']; ?>"><?php echo $personInfo['nickname']; ?></a></span></div>
	<div class="peopleCount"></div>
	<div class="peopleRecent">
		<div class="visit"><?php
			for($i=0; $i<count($personInfo['recent']['visit']['toys']); $i++){
				$curToy = $personInfo['recent']['visit']['toys'][$i];?>

			<div class="toyViewSmallList">
				<ul>
					<li class="id"><?php echo $curToy['id']; ?></li>
					<li class="pic"><?php echo $curToy['pic']; ?></li>
					<li class="title"><?php echo $curToy['title']; ?></li>
				</ul>
			</div><?php
			}?>

		</div>
		<div class="comment">
		</div>
	</div>
</div>
</body>