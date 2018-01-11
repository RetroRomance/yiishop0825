<?php
////            //logo
////            $html1='';
////           foreach($model2 as $good){
////            $html1.='<h3><strong>'.$good->content.'</strong></h3>';
////           }
////            $html1.='<div class="preview fl">';
////            $html1.='<div class="midpic">';
////                   foreach($model as $goods) {
////                   $html1.='<a href="'.$goods->logo.'" class="jqzoom" rel="gal1">';
////                   $html1.='<img src="'.$goods->logo.'" alt="" width="350px" />';
////                   $html1.='</a>';
////                   }
////
////
////
////
////            $html2='';
////                //zhanshi
////                  foreach($model as $goods){
////                      $html2='<li class="cur">';
////                      $html2='<a class="zoomThumbActive" href="javascript:void(0);"rel="{gallery: "gal1", smallimage: "'.$goods->logo.'",largeimage: "'.$goods->logo.'">';
////                      $html2='<img src="'.$goods->logo.'" alt=""/></a>';
////                      $html2='</li>';
////                  }
////
////
////                foreach($model3 as $gg){
////
////                }
////$html2='<li>
////    <a href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: \'<?=$gg->path?><!--',largeimage: '/images/preview_l2.jpg'}">-->
<!--//        $html2='<img src=".$gg->path.'"></a>';-->
<!--//    $html2='</li>-->
<!--//-->
<!--//                               -->
<!--//                    //xq1-->
<!--//                    --><?php //foreach($model as $goods):?>
<!--//                        <ul>-->
<!--//-->
<!--//                            <li><span>商品编号： </span>971344</li>-->
<!--//                            <li class="market_price"><span>定价：</span><em>￥--><?//=$goods->market_price?><!--</em></li>-->
<!--//                            <li class="shop_price"><span>本店价：</span> <strong>￥--><?//=$goods->shop_price?><!--</strong> <a href="">(降价通知)</a></li>-->
<!--//                            <li><span>上架时间：</span>--><?//=$goods->create_time?><!--</li>-->
<!--//                            <li class="star"><span>热度：</span> --><?//=$goods->view_times?><!--</a></li> <!-- 此处的星级切换css即可 默认为5星 star4 表示4星 star3 表示3星 star2表示2星 star1表示1星 -->-->
<!--//-->
<!--//                        </ul>-->
<!--//                        <form action="--><?//=\yii\helpers\Url::to(['goods/addto-cart'])?><!--" method="get" class="choose">-->
<!--//                            <ul>-->
<!--//-->
<!--//                                <li>-->
<!--//                                    <dl>-->
<!--//                                        <dt>购买数量：</dt>-->
<!--//                                        <dd>-->
<!--//                                            <a href="javascript:;" id="reduce_num"></a>-->
<!--//                                            <input type="text" name="amount" value="1" class="amount"/>-->
<!--//                                            <a href="javascript:;" id="add_num"></a>-->
<!--//                                        </dd>-->
<!--//                                    </dl>-->
<!--//                                </li>-->
<!--//-->
<!--//                                <li>-->
<!--//                                    <dl>-->
<!--//                                        <dt>&nbsp;</dt>-->
<!--//                                        <dd>-->
<!--//                                            <input type="hidden" name="goods_id" value="--><?//=$goods->id?><!--"/>-->
<!--//                                            <input type="submit" value="" class="add_btn" />-->
<!--//                                        </dd>-->
<!--//                                    </dl>-->
<!--//                                </li>-->
<!--//-->
<!--//                            </ul>-->
<!--//                        </form>-->
<!--//                   -->
<!--//-->
<!--//               //xq2-->
<!--//-->
<!--//                            --><?php //foreach($model as $goods):?>
<!--//                            <li><span>商品名称：</span>--><?//=$goods->name?><!--</li>-->
<!--//                            <li><span>商品编号：</span>--><?//=$goods->sn?><!--</li>-->
<!--//                            <li><span>品牌：</span>--><?//=$goods->brand_id?><!--</li>-->
<!--//                            <li><span>上架时间：</span>--><?//=$goods->create_time?><!--</li>-->
<!--//                            <li><span>商品毛重：</span>2.47kg</li>-->
<!--//                            <li><span>商品产地：</span>中国大陆</li>-->
<!--//                            <li><span>显卡：</span>集成显卡</li>-->
<!--//                            <li><span>触控：</span>触控</li>-->
<!--//                            <li><span>厚度：</span>正常厚度（>25mm）</li>-->
<!--//                            <li><span>处理器：</span>Intel i5</li>-->
<!--//                            <li><span>尺寸：</span>12英寸</li>-->
<!--//                     -->
<!--//-->
<!--//                            -->
<!--//                $tpl='tpl.tpl';-->
<!--//                $content=file_get_contents($tpl);-->
<!--//                $content=str_replace('{%logo%}',$html1,$content);-->
<!--//                -->