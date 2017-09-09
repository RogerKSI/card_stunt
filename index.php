	<!doctype html>
	<html>
		<head>
			<meta charset="UTF-8">
			<script type="text/javaScript" src="./resources/js/jquery.min.js"></script>
			<script type="text/javaScript" src="./resources/js/three.js"></script>
			<script type="text/javaScript" src="./resources/js/OrbitControls.js"></script>
			<style>
				body {
					background-color: #000;
					margin: 0px;
					overflow: hidden;
				}
			</style>
			<title>Card stunt</title>
			
			<script>

				$( document ).ready(function() {					
					var parent, renderer, scene, camera, controls;
					var imgs=[];
					var count=0;
					var points=[];
					var ok=0;
					var textureLoader = new THREE.TextureLoader();
					
					function init() {
						// renderer
						renderer = new THREE.WebGLRenderer();
						renderer.setSize( window.innerWidth, window.innerHeight );
						document.body.appendChild( renderer.domElement );
					
						// scene
						scene = new THREE.Scene();
						
						// camera
						camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 500 );
						camera.position.set( 0, 0, 50);
					
						// controls
						controls = new THREE.OrbitControls( camera );
						controls.minDistance = 10;
						controls.maxDistance = 100;
					
						// axes
						//scene.add( new THREE.AxisHelper( 30 ) );
									
						$.ajax({
							type: "GET",
							async: false,
							url: './template.php',
							success: function (dataJSON) {
								var data = jQuery.parseJSON(dataJSON);
								$.each(data, function (index, result) {
									points.push(result);
								});
								
							},error: function (){
								location.reload();
							}
						});
				
						// parent
						parent = new THREE.Object3D();
						scene.add( parent );					
			
						for ( var i = 0; i < 100; i++ ) {
							var material = new THREE.MeshBasicMaterial( {
								color: Math.random() * 0x808008 + 0x808080
							} );

							var particle = new THREE.Mesh( new THREE.BoxBufferGeometry(1, 1, 1), material );
							particle.position.x = Math.random() * 120-60;
							particle.position.y = Math.random() * 120-60;
							particle.position.z = Math.random() * 120-60;
							particle.scale.x = particle.scale.y = Math.random() * 0.5;
							parent.add( particle );
						}
									
					}
									
					function animate() {
									
						requestAnimationFrame( animate );
						if(count%925==0){
							
							if(ok){
								for(var i=0;i<points.length;i++)
									points[i]['chk']=0;
							}
							
							for(var i=0;i<imgs.length;i++){
								if(ok){
									var co = 0;
									var mark = parseInt(Math.random()*points.length);
									while(points[mark]['chk']!=0 && co<=points.length){
										co++;
										mark = parseInt(Math.random()*points.length);
									}
									points[mark]['chk']=1;
									imgs[i].aimx = points[mark]['x'];
									imgs[i].aimy = points[mark]['y'];
									imgs[i].aimz = 0;
									
								}else{
								
									imgs[i].aimx = Math.random()*130-65;
									imgs[i].aimy = Math.random()*130-65;
									imgs[i].aimz = Math.random()*130-65;
								}
							}
							
							
							ok=!ok;
							count=0;
						}
						
						var mul = ok?-1:1;
						parent.rotation.x -= 0.007 * mul;
						parent.rotation.y -= 0.007 * mul;
						parent.rotation.z -= 0.007 * mul;
					
						for(var i=0;i<imgs.length;i++){
						
							var deltax = imgs[i].aimx - imgs[i].position.x;
							var deltay = imgs[i].aimy - imgs[i].position.y;
							var deltaz = imgs[i].aimz - imgs[i].position.z;
							
							var scale =  Math.random();
							if(Math.abs(deltax)>=0.1)
								imgs[i].position.x += scale * (deltax/Math.abs(deltax)/3.0);
							if(Math.abs(deltay)>=0.1)
								imgs[i].position.y += scale * (deltay/Math.abs(deltay)/3.0);
							if(Math.abs(deltaz)>=0.1)
								imgs[i].position.z += scale * (deltaz/Math.abs(deltaz)/3.0);
							
							imgs[i].rotation.x += 0.005;
							imgs[i].rotation.y += 0.005;
							
						}
						
						count++;
						
						controls.update();
					
						renderer.render( scene, camera );			
					
					}
									
					var loadcard = function() {							
						
						$.ajax({
							url: "./getcard.php",
							type: 'POST',
							success: function (dataJSON) {
								var data = jQuery.parseJSON(dataJSON);
								$.each(data, function (index, result) {

									var img = textureLoader.load(result);
									
									var material = new THREE.MeshBasicMaterial( { map: img, overdraw: 0.5 } );
									var objimg = new THREE.Mesh(new THREE.BoxBufferGeometry(0.35, 0.35, 0.35), material);
									//var objimg = new THREE.Sprite(new THREE.SpriteMaterial({ map: img, color: 0xffffff}));
									
									objimg.position.x = Math.random()*130-65;	
									objimg.position.y = Math.random()*130-65;	
									objimg.position.z = Math.random()*130-65;
									
									objimg.aimx = points[index]['x'];
									objimg.aimy = points[index]['y'];
									objimg.aimz = 0;
								
									objimg.scale.x = objimg.scale.y = objimg.scale.z = 2.5;
									imgs.push(objimg);
									parent.add( objimg );
								});
								
							}

						});
					};
			
					init();
					loadcard();			
					
					animate();
				}); 
			</script>
		</head>    
		
		<body>
	 
		</body>
	</html>
