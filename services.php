<?php 
$cid = isset($_GET['cids']) ? $_GET['cids'] : 'all';
?>
<div class="content py-5">
    <h3 class="">Our Services</h3>
<hr>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <h3>Categories</h3>
                <div class="list-group border border-success">
                    <div class="list-group-item list-group-item-action">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="category_all" value="all" <?= $cid =='all' ? "checked" :"" ?>>
                          <label for="category_all" class="custom-control-label">All</label>
                        </div>
                    </div>
                    <?php 
                    $cat_qry = $conn->query("SELECT * FROM `category_list` where delete_flag = 0");
                    while($row = $cat_qry->fetch_assoc()):
                    ?>
                    <div class="list-group-item list-group-item-action">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input category-item" type="checkbox" id="category_<?= $row['id'] ?>" value="<?= $row['id'] ?>" <?= $cid=='all' || in_array($row['id'],explode(',',$cid)) ? "checked" : "" ?>>
                            <label for="category_<?= $row['id'] ?>" class="custom-control-label"><?= $row['name'] ?></label>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="col-md-8">
            <div class="list-group border border-success" id="service-list">
            <?php 
                $categories = $conn->query("SELECT * FROM `category_list`");
                $cat_arr = array_column($categories->fetch_all(MYSQLI_ASSOC),'name','id');
                $cwhere = "";
                if($cid != 'all'){
                    $cwhere .= " and ";
                    $_cw = "";
                    foreach(explode(',',$cid) as $v){
                        if(!empty($_cw)) $_cw .= " or ";
                        $_cw .= "CONCAT('|',REPLACE(category_ids,',','|,|'),'|') LIKE '%|{$v}|%'";
                    }
                    $cwhere .= "({$_cw})";
                }
                $services = $conn->query("SELECT * FROM `service_list` where delete_flag = 0 {$cwhere}  order by `name` asc");
                while($row = $services->fetch_assoc()):
                    $for = '';
                    foreach(explode(',',$row['category_ids']) as $v){
                        if(isset($cat_arr[$v])){
                            if(!empty($for)) $for .= ", ";
                            $for.= $cat_arr[$v];
                        }
                    }
                    $for = empty($for) ? "N/A" : $for;
            ?>
                <div class="text-decoration-none list-group-item rounded-0 service-item">
                    <a class="d-flex w-100 text-dark align-items-center" href="#service_<?= $row['id'] ?>" data-toggle="collapse">
                        <div class="col-11">
                            <h3 class="mb-0"><b><?= ucwords($row['name']) ?></b></h3>
                            <small><em>(<?= $for ?>)</em></small>
                        </div>
                        <div class="col-1 text-right">
                            <i class="fa fa-plus collapse-icon"></i>
                        </div>
                    </a>
                    <div class="collapse" id="service_<?= $row['id'] ?>">
                        <hr class="">
                        <div class="row align-items-top">
                            <div class="col-10">
                            </div>
                            <div class="col-2 text-right">
                                <div class="mx-3"><span class="fa fa-tags"></span> <?= number_format($row['fee'],2) ?></div>
                            </div>
                        </div>
                        <p class="mx-3"><?= html_entity_decode($row['description']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php ?>
        </div>
            </div>
        </div>
    </div>
</div>

<script>
    function _category_filter(){
        if($('#category_all').is(":checked") == true){
            location.href="./?page=services"
        }else{
            var cids = [];
            $('.category-item:checked').each(function(){
                cids.push($(this).val())
            })
            cids = encodeURI(cids.join(","))
            location.href="./?page=services&cids="+cids
        }
    }
    $(function(){
        $('#category_all').change(function(){
            if($(this).is(":checked") == true){
                $('.category-item').prop("checked",true)
                _category_filter()
            }else{
                $('.category-item').prop("checked",false)
            }
        })
        $('.category-item').change(function(){
            if($('.category-item:checked').length < $('.category-item').length){
                $('#category_all').prop("checked",false)
            }else{
                $('#category_all').prop("checked",true)
            }
            _category_filter()
        })
        $('.collapse').on('show.bs.collapse', function () {
            $(this).parent().siblings().find('.collapse').collapse('hide')
            $(this).parent().siblings().find('.collapse-icon').removeClass('fa-plus fa-minus')
            $(this).parent().siblings().find('.collapse-icon').addClass('fa-plus')
            $(this).parent().find('.collapse-icon').removeClass('fa-plus fa-minus')
            $(this).parent().find('.collapse-icon').addClass('fa-minus')
            console.log($(this).parent().offset().top - $(this).parent().height())
             $("html, body").animate({scrollTop:$(this).parent().offset().top},'fast')
        })
        $('.collapse').on('hidden.bs.collapse', function () {
            $(this).parent().find('.collapse-icon').removeClass('fa-plus fa-minus')
            $(this).parent().find('.collapse-icon').addClass('fa-plus')
        })

        $('#search').on("input",function(e){
            var _search = $(this).val().toLowerCase()
            $('#service-list .service-item').each(function(){
                var _txt = $(this).text().toLowerCase()
                if(_txt.includes(_search) === true){
                    $(this).toggle(true)
                }else{
                    $(this).toggle(false)
                }
                if($('#service-list .service-item:visible').length <= 0){
                    $("#no_result").show('slow')
                }else{
                    $("#no_result").hide('slow')
                }
            })
        })
    })
    
</script>