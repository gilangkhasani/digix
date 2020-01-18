<ul class="sidebar-menu" data-widget="tree">
<?php
/* if($_SESSION[$sessionname]->roles =="su"){
    $optionroles = "";
}else{
    $optionroles = "and (authorized_roles is null or authorized_roles ='' or authorized_roles LIKE '%".$_SESSION[$sessionname]->roles."%')";
} */
$optionroles = "and (authorized_roles is null or authorized_roles ='' or authorized_roles LIKE '%".$_SESSION[$sessionname]->roles."%')";
$query0 = "select * from tbl_menu where level_menu=0 $optionroles order by order_menu";
$data0 = db_query2list($query0);
foreach($data0 as $val0){
    if($val0->type_menu=="header"){
        echo '<li class="header">'.$val0->nama_menu.'</li>';
        $query1 = "select * from tbl_menu where level_menu=1 and parent_menu=$val0->id_menu $optionroles order by order_menu";
        $data1 = db_query2list($query1);
        foreach($data1 as $val1){
            if($val1->type_menu=="link"){
                if($val1->target_menu==''){ $target = 'target="_SELF"';}{$target='target="'.$val1->target_menu.'"';}
                echo '<li><a href="'.url($val1->url_menu).'" '.$target.'><i class="fa '.$val1->icon_menu.' '.$val1->color_menu.'"></i> <span>'.$val1->nama_menu.'</span></a></li>';
            }elseif($val1->type_menu=="treeview"){
                echo '<li class="treeview">';
                echo '  <a href="#"><i class="fa '.$val1->icon_menu.' '.$val1->color_menu.'"></i> <span>'.$val1->nama_menu.'</span>';
                echo '    <span class="pull-right-container">';
                echo '       <i class="fa fa-angle-left pull-right"></i>';
                echo '    </span>';
                echo '  </a>';
                echo '  <ul class="treeview-menu">';
                $query2 = "select * from tbl_menu where level_menu=2 and parent_menu=$val1->id_menu  $optionroles order by order_menu";
                $data2 = db_query2list($query2);
                foreach($data2 as $val2){
                    if($val2->type_menu=="link"){
                        if($val2->target_menu==''){ $target = 'target="_SELF"';}{$target='target="'.$val2->target_menu.'"';}
                        echo '<li><a href="'.url($val2->url_menu).'" '.$target.'><i class="fa '.$val2->icon_menu.' '.$val2->color_menu.'"></i> <span>'.$val2->nama_menu.'</span></a></li>';
                    }elseif($val2->type_menu=="treeview"){
                        echo '<li class="treeview">';
                        echo '  <a href="#"><i class="fa '.$val2->icon_menu.' '.$val2->color_menu.'"></i> <span>'.$val2->nama_menu.'</span>';
                        echo '    <span class="pull-right-container">';
                        echo '       <i class="fa fa-angle-left pull-right"></i>';
                        echo '    </span>';
                        echo '  </a>';
                        echo '  <ul class="treeview-menu">';
                        $query3 = "select * from tbl_menu where level_menu=3 and parent_menu=$val2->id_menu  $optionroles  order by order_menu";
                        $data3 = db_query2list($query3);
                        foreach($data3 as $val3){
                            if($val3->type_menu == "link" ){
                                if($val3->target_menu==''){ $target = 'target="_SELF"';}{$target='target="'.$val3->target_menu.'"';}
                                echo '<li><a href="'.url($val3->url_menu).'" '.$target.'><i class="fa '.$val3->icon_menu.' '.$val3->color_menu.'"></i> <span>'.$val3->nama_menu.'</span></a></li>';
                            }elseif($val3->type_menu=="treeview"){
                                echo '<li class="treeview">';
                                echo '  <a href="#"><i class="fa '.$val3->icon_menu.' '.$val3->color_menu.'"></i> <span>'.$val3->nama_menu.'</span>';
                                echo '    <span class="pull-right-container">';
                                echo '       <i class="fa fa-angle-left pull-right"></i>';
                                echo '    </span>';
                                echo '  </a>';
                                echo '  <ul class="treeview-menu">';
                                $query4 = "select * from tbl_menu where level_menu=4 and parent_menu=$val3->id_menu  $optionroles order by order_menu";
                                $data4 = db_query2list($query4);
                                foreach($data4 as $val4){
                                    if($val4->type_menu=="link"){
                                        if($val4->target_menu==''){ $target = 'target="_SELF"';}{$target='target="'.$val4->target_menu.'"';}
                                        echo '<li><a href="'.url($val4->url_menu).'" '.$target.'><i class="fa '.$val4->icon_menu.' '.$val4->color_menu.'"></i> <span>'.$val4->nama_menu.'</span></a></li>';
                                    }elseif($val4->type_menu=="treeview"){
                                        echo '<li class="treeview">';
                                        echo '  <a href="#"><i class="fa '.$val4->icon_menu.' "></i> <span>'.$val4->nama_menu.'</span>';
                                        echo '    <span class="pull-right-container">';
                                        echo '       <i class="fa fa-angle-left pull-right"></i>';
                                        echo '    </span>';
                                        echo '  </a>';
                                        echo '  <ul class="treeview-menu">';
                                        $query5 = "select * from tbl_menu where level_menu=5 and parent_menu=$val4->id_menu $optionroles  order by order_menu";
                                        $data5 = db_query2list($query5);
                                        foreach($data5 as $val5){ //----- link 5
                                            if($val5->type_menu=="link"){
                                                if($val5->target_menu==''){ $target = 'target="_SELF"';}{$target='target="'.$val5->target_menu.'"';}
                                                echo '<li><a href="'.url($val5->url_menu).'" '.$target.'><i class="fa '.$val5->icon_menu.' '.$val5->color_menu.'"></i> <span>'.$val5->nama_menu.'</span></a></li>';
                                            }elseif($val5->type_menu=="treeview"){
                                                echo '<li class="treeview">';
                                                echo '  <a href="#"><i class="fa '.$val5->icon_menu.' '.$val5->color_menu.'"></i> <span>'.$val5->nama_menu.'</span>';
                                                echo '    <span class="pull-right-container">';
                                                echo '       <i class="fa fa-angle-left pull-right"></i>';
                                                echo '    </span>';
                                                echo '  </a>';
                                                echo '  <ul class="treeview-menu">';
                                                // untuk level selanjut nya
                                                echo '  </ul>';
                                                echo '</li>';
                                            }
                                        } //----link 5
                                        echo '  </ul>';
                                        echo '</li>';
                                    }
                                }
                                echo '  </ul>';
                                echo '</li>';
                            }
                        }
                        echo '  </ul>';
                        echo '</li>';
                    }
                }
                echo '  </ul>';
                echo '</li>';
            }
        }
    }
}

?>
</ul>