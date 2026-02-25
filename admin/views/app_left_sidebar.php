<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar ">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel" style="height: 58px;">
			<div class="pull-left image">
				<?php
				if(file_exists(DNADMIN._.$session_user_data->profile)){?>
					<img src="<?=DNADMIN._.$session_user_data->profile;?>" class="img-circle" alt="User Image">
				<?php }else{?>
					<span style="display: block; padding: 8px 18px; border-radius: 100%; background: #333d41; color: #fff; font-size: 20px; font-weight: 600; border"><?php echo substr($session_user_data->firstname,0,1);?></span>
				<?php }?>
			</div>
			<div class="pull-left info">
			  <p><?=$session_user_data->firstname?></p>
			  <!-- Status -->
			  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		
		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">MENU</li>
			<!-- Optionally, you can add icons to the links -->
            
              
            <li class="treeview <?php if($url_struc['tree'] == "app" && $url_struc['app-idname']=="EventApp"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-cog blueapp-col"></i> <span> Offres d’emplo</span></a>
                <ul class="treeview-menu">
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/serie/list"><i class="fa fa-circle-o icon"></i> <span>Liste des offres</span></a>
                    </li>
                    
				</ul>
            </li>

           

            
            <li class="header hidden">REPORTS</li>
           
            
            <li class="treeview hidden <?php if($url_struc['tree'] == "app" && $url_struc['app-idname']=="EventApp"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-book blueapp-col"></i> <span> Subscriptions</span></a>
                <ul class="treeview-menu">
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/report/customersubscriptions"><i class="fa fa-circle-o icon"></i> <span>Customer subscriptions</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/report/salesrevenuebyserie"><i class="fa fa-circle-o icon"></i> <span>Revenues by serie</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/report/customersubscriptionslog"><i class="fa fa-circle-o icon"></i> <span>Subscriptions log</span></a>
                    </li>
				</ul>
            </li>
            
            
			<li class="header">COMPANY</li>
            <li class="treeview <?php if($url_struc['tree'] == "company" && $url_struc['trunk']=="users"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-fort-awesome"></i> <span>Comptes</span></a>
                <ul class="treeview-menu">
					<li class="<?php if($url_struc['tree']=="company" && $url_struc['trunk']=="users"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/users/list"><i class="fa fa-circle-o icon"></i><span>Utilisateurs</span></a></li>
                    
				</ul>
            </li>
            <li class=""><a href="<?=DNADMIN?>/logout"><i class="fa fa-sign-out"></i> <span>Déconnexion</span></a></li>
			
		</ul><!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->