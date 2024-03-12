<div class="platform-heat-map-wrap container">
    <form method="get" name="heat-map" id="heat-map">
        <div class="row">
            <div class="col-lg-12"> 
            <label class="question">View heatmaps for  <span class="platform_type_radios">
                    <input type="radio" <?php echo ( $platform_type == 1 ? 'checked="checked"' : '' ) ?> name="platform_type" class="ctp-nav" id="platform_type_d2c" value="1"> 
                    <label for="platform_type_d2c"><span><span></span></span></label> 
                </span> D2C platforms <span class="platform_type_radios">
                    <input type="radio" <?php echo ( $platform_type == 2 ? 'checked="checked"' : '' ) ?> name="platform_type" class="ctp-nav" id="platform_type_adviser"  value="2"> 
                    <label for="platform_type_adviser"><span><span></span></span></label> 
                </span> Adviser platforms</label>
            </div>
        </div>
    </form>
    <div class="heatmap-legend">
      <div><h4>Heatmap legend</h4></div>
      <div class="heatmap-legend__inner">
        <div class="low-priced-fees">Low fees<span></span></div>
        <div class="mid-priced-fees">Mid fees<span></span></div>
        <div class="high-priced-fees">High fees<span></span></div>
      </div>
    </div>
    <div class="heat_map_listing">
    <style>
        
</style>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('body').on('change','#heat-map',function(){
            jQuery(this).trigger('submit');
        });
    });
</script>
        <div class="fusion-row">
            <!-- 100% in ISA HTML Code -->
            <div class="hundred-percentage-isa-main cplat-infotable">
                <div class="cplat-infotable-wrapper">
                    <h2>Portfolio with 100% in ISAs</h2>
                    <table class="cplat-table charges-table">
                        <?php
                            if( $heat_map_listing_isa_only ){
                                echo '<tr class="heading_tr">';
                                echo '<td></td>';
                                foreach( $heat_map_listing_isa_only['average_cost_isa_hundred_percent_total'] as $key => $value ){
                                    if( $key > 999999 ){
                                        $ranges = round(($key/1000000),1).'m';
                                    }else{
                                        $ranges = number_format($key);
                                    }
                                    echo '<td>£'.$ranges.'</td>';
                                }
                                echo '</tr>';
                                $count_of_platforms = $heat_map_listing_isa_only['count_of_platforms'];
                                foreach( $heat_map_listing_isa_only as $heat_map ){
                                    $platform_name =  $heat_map['platform_name'];
                                    if($platform_name){
                                        echo '<tr class="values_tr">';
                                        echo '<td class="charges-label">'.$platform_name.'</td>';
                                        foreach ($heat_map['hundred_percent_in_ISA'] as $key => $value) {
                                            $average = $heat_map_listing_isa_only['average_cost_isa_hundred_percent_total'][$key] / $count_of_platforms ;
                                            $investment_level = $value['investment_level'];
                                            $bg_color_class = '';
                                            if( floatval($average) < floatval($investment_level) ){
                                                $total = $average +( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'amber';
                                                }else{
                                                    $bg_color_class = 'red';
                                                }
                                            }else{
                                                $total = $average - ( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'green';
                                                }else{
                                                    $bg_color_class = 'green';
                                                }
                                            }
                                            //echo '<td class="'.$bg_color_class.'">'.number_format($value['cost'],2).'\n'.number_format($investment_level,2).' % </td>';    
                                            echo '<td class="'.$bg_color_class.'">'.number_format($investment_level,2).'% </td>';    
                                        }
                                        echo '</tr>';
                                    }
                                }
                                echo '<tr class="extra-tr"><td colspan="7"></td></tr>';
                                echo '<tr class="average_tr">';
                                    echo '<td>Average</td>';
                                    foreach ($heat_map_listing_isa_only['average_cost_isa_hundred_percent_total'] as $key => $value) {
                                        echo '<td>'.number_format($value / $count_of_platforms ,2).'% </td>';    
                                    }
                                echo '</tr>';
                            }
                        ?>
                        
                    </table>
                </div>
            </div>
           <!-- 50% in SIPP HTML Code -->
            <div class="fifty-percentage-sipp-main cplat-infotable">
                <div class="cplat-infotable-wrapper">
                    <h2>Portfolio with 50% in a Sipp and 25% in both GIA and ISAs</h2>
                    <table class="cplat-table charges-table">
                        <?php
                            if( $heat_map_listing_both_isa_gia ){
                                echo '<tr class="heading_tr">';
                                echo '<td></td>';
                                foreach( $heat_map_listing_both_isa_gia['average_cost_fifty_percent_total'] as $key => $value ){
                                    if( $key > 999999 ){
                                        $ranges = round(($key/1000000),1).'m';
                                    }else{
                                        $ranges = number_format($key);
                                    }
                                    echo '<td>£'.$ranges.'</td>';
                                }
                                echo '</tr>';
                                $count_of_platforms = $heat_map_listing_both_isa_gia['count_of_platforms'];
                                foreach( $heat_map_listing_both_isa_gia as $heat_map ){
                                    $platform_name =  $heat_map['platform_name'];
                                    if($platform_name){
                                        echo '<tr class="values_tr">';
                                        echo '<td class="charges-label">'.$platform_name.'</td>';
                                        foreach ($heat_map['fifty_percent_in_SIPP'] as $key => $value) {
                                            $average = $heat_map_listing_both_isa_gia['average_cost_fifty_percent_total'][$key] / $count_of_platforms;
                                            $average = number_format($average,2);
                                            $investment_level = $value['investment_level'];
                                            $bg_color_class = '';
                                            if( floatval($average) < floatval($investment_level) ){
                                                $total = $average +( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'amber';
                                                }else{
                                                    $bg_color_class = 'red';
                                                }
                                            }else{
                                                $total = $average - ( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'green';
                                                }else{
                                                    //$bg_color_class = 'red';
                                                    $bg_color_class = 'green';
                                                }
                                            }
                                            //echo '<td class="'.$bg_color_class.'">'.number_format($value['cost'],2).'<br/>'.number_format($investment_level,2).' % </td>';    
                                            echo '<td class="'.$bg_color_class.'">'.number_format($investment_level,2).'% </td>';    
                                        }
                                        echo '</tr>';
                                    }
                                }
                                echo '<tr class="extra-tr"><td colspan="7"></td></tr>';
                                echo '<tr class="average_tr">';
                                    echo '<td>Average</td>';
                                    foreach ($heat_map_listing_both_isa_gia['average_cost_fifty_percent_total'] as $key => $value) {
                                        echo '<td>'.number_format($value / $count_of_platforms ,2).'% </td>';    
                                    }
                                echo '</tr>';
                            }
                        ?>
                        
                    </table>
                </div>
            </div>
            <!-- 100% in SIPP HTML Code -->
            <div class="hundred-percentage-sipp-main cplat-infotable">
                <div class="cplat-infotable-wrapper">
                    <h2>Portfolio with 100% in a Sipp</h2>
                    <table class="cplat-table charges-table">
                        <?php
                            if( $heat_map_listing_sipp_only ){
                                echo '<tr class="heading_tr">';
                                echo '<td></td>';
                                foreach( $heat_map_listing_sipp_only['average_cost_hundred_percent_total'] as $key => $value ){
                                    if( $key > 999999 ){
                                        $ranges = round(($key/1000000),1).'m';
                                    }else{
                                        $ranges = number_format($key);
                                    }
                                    echo '<td>£'.$ranges.'</td>';
                                }
                                echo '</tr>';
                                $count_of_platforms = $heat_map_listing_sipp_only['count_of_platforms'];

                                foreach( $heat_map_listing_sipp_only as $heat_map ){
                                    $platform_name =  $heat_map['platform_name'];
                                    if($platform_name){
                                        echo '<tr class="values_tr">';
                                        echo '<td class="charges-label">'.$platform_name.'</td>';
                                        foreach ($heat_map['hundred_percent_in_SIPP'] as $key => $value) {
                                            $average = $heat_map_listing_sipp_only['average_cost_hundred_percent_total'][$key] / $count_of_platforms ;
                                            $investment_level = $value['investment_level'];
                                            $bg_color_class = '';
                                            if( floatval($average) < floatval($investment_level) ){
                                                $total = $average +( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'amber';
                                                }else{
                                                    $bg_color_class = 'red';
                                                }
                                            }else{
                                                $total = $average - ( $average * 0.25 ); 
                                                $min = ( $total > $average ? $average : $total );
                                                $max = ( $total > $average ? $total : $average );
                                                if( $investment_level >= $min && $investment_level <= $max ){
                                                    $bg_color_class = 'green';
                                                }else{
                                                    $bg_color_class = 'green';
                                                }
                                            }
                                            //echo '<td class="'.$bg_color_class.'">'.number_format($value['cost'],2).'\n'.number_format($investment_level,2).' % </td>';    
                                            echo '<td class="'.$bg_color_class.'">'.number_format($investment_level,2).'% </td>';    
                                        }
                                        echo '</tr>';
                                    }
                                }
                                echo '<tr class="extra-tr"><td colspan="7"></td></tr>';
                                echo '<tr class="average_tr">';
                                    echo '<td>Average</td>';
                                    foreach ($heat_map_listing_sipp_only['average_cost_hundred_percent_total'] as $key => $value) {
                                        echo '<td>'.number_format($value / $count_of_platforms ,2).'% </td>';    
                                    }
                                echo '</tr>';
                            }
                        ?>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



