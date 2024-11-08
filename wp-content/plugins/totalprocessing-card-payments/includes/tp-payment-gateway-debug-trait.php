<?php

trait TotalProcessingGatewayDebugTrait{
    public function checkLogLevelViaOppwaResultCode( $code ){
        //oppwaLogLevels
        $array      = $this->arrStaticData( 'oppwaLogLevels' );
        $code       = (string)$code;
        $retVal     = [$code,'debug','non_matched'];
        foreach($array as $level => $statuses){
            foreach($statuses as $status => $pattern){
                preg_match($pattern, $code, $matches);
                if(count($matches) > 0){
                    $retVal[1] = $level;
                    $retVal[2] = $status;
                    break;
                }
            }
        }
        return $retVal;
    }

    protected function writeLog( $label, $obj, $level = false, $uuid = null ){
        $debug     = $this->get_option( 'serversidedebug' );
        if( $level == 'critical' ||  $level == 'emergency' ){
            $debug = 'yes';
        }
        if( $debug != 'yes' ){
            return;
        }

        if( !isset( $this->logLevels ) || !in_array( $level, $this->logLevels )){
            return;
        }

        $logger                   = wc_get_logger();
        $GLOBALS['logcounter']    = isset( $GLOBALS['logcounter'] ) ? $GLOBALS['logcounter'] + 1 : 1;

        $context    = array( 'source' => $this->id );
        $cartHash   = !empty( $uuid ) ? $uuid : ( WC()->cart ? WC()->cart->get_cart_hash() : null );
        $logMessage = '['.$cartHash.'] Stack #' . $GLOBALS['logcounter'] . " : " . $label . ( $obj != null ? "\n" . wc_print_r( $obj, true ) : '' );

        switch ( $level ) {
            case 'critical':
                $logger->critical( $logMessage, $context );
                break;
            case 'debug':
                $logger->debug( $logMessage, $context );
                break;
            case 'emergency':
                $logger->emergency( $logMessage, $context );
                break;
            case 'error':
                $logger->error( $logMessage, $context );
                break;
            case 'warning':
                $logger->warning( $logMessage, $context );
                break;
            default:
               $logger->info( $logMessage, $context );
        }

        return true;
    }

    public function arrStaticData($key){
        $array= include dirname( __FILE__ ) . '/payment-gateway-static-data-array.php';
        if( isset( $array[ $key ] ) ){
            return $array[ $key ];
        }
        return [];
    }

    public function getOppwaMessageByCode( $code ){
        if( preg_match( "/^(900\.[1234]00|000\.400\.030)/", $code ) ){
            return "We are not sure on the state of your payment, please contact the store, before attempting payment again.";
        }
        $array = include dirname( __FILE__ ) . '/error-codes-and-messages.php';
        if( isset( $array[ $code ] ) ){
            return $array[ $code ];
        }
        return "";
    }

    public function generateDebuggingLogsHTML(){
        global $wp_version;
        $files             = $this->get_wc_logfiles();
        $options           = '';
        $firstFileHandle   = !empty( $_POST['fld_log_handle'] ) ? $_POST['fld_log_handle'] : '';
        foreach( $files AS $key => $file ){
            $firstFileHandle = empty( $firstFileHandle ) ? $file : $firstFileHandle;
            $options .= '<option value="'.$file.'" ' . ( $file == $firstFileHandle ? 'selected' : '' ) . '>'.$key.'</option>';
        }
        $html    = '<div id="logforwardstatuscontainer"></div>
                    <table class="wc_status_table widefat" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td style="text-align:left;width:1%;vertical-align:middle;">
                                    Selected:
                                </td>
                                <td style="text-align:left;vertical-align:middle;width:10%;">
                                    ' . $firstFileHandle . '
                                </td>
                                <td style="text-align:right;vertical-align:middle;">
                                    Debug Files
                                </td>
                                <td style="width:2%;vertical-align:middle;">
                                    <select name="fld_log_handle">
                                    ' . $options . ' 
                                    </select>
                                </td>
                                <td style="width:2%;vertical-align:middle;">
                                    <button type="submit">View</button>
                                </td>
                                <td style="width:5%;vertical-align:middle;" nowrap>
                                    <button type="button" class="send_log_to_tp_support" data-action="' . $this->id . '_forward_debugdata_to_tp_support">Send To TP</button>
                                </td>
                                <td style="width:5%;vertical-align:middle;">
                                    <button type="button" class="download_log_file" data-action="' . $this->id . '_download_debugdata_file">Download</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <div id="log-viewer">
                        <pre>'.( empty( $firstFileHandle ) ? '' : esc_html( file_get_contents( WC_LOG_DIR . $firstFileHandle ) ) ).'</pre>
                    </div>
                    <script type="text/javascript">
                    jQuery(function($){
                        $(document).on("click", ".send_log_to_tp_support", function(e){
                            e.preventDefault();
                            var el       = $(this);
                            var filename = $("select[name=fld_log_handle]").val();
                            var action = el.data("action");
                            return wp.ajax.post(action,{"filename":filename})
                                .then(function(response) {
                                    console.log(response);
                                    if(response.message){
                                        $("#logforwardstatuscontainer").html(response.message);
                                    }
                                });
                        });
                        $(document).on("click", ".download_log_file", function(e){
                            e.preventDefault();
                            var el       = $(this);
                            var filename = $("select[name=fld_log_handle]").val();
                            var action   = el.data("action");
                            var durl     = "'.admin_url('admin-ajax.php').'?action="+action+"&file="+filename;
                            window.location = durl;
                        });
                    });
                    </script>';
        return $html;
    }

    public function get_wc_logfiles(){
        $log_files  = WC_Log_Handler_File::get_log_files();
        krsort( $log_files );
        $arr        = [];
        foreach( $log_files AS $key => $file ){
            if( strpos( $key, $this->id ) !== false ){
                $arr[$key] = $file;
            }
        }
        return $arr;
    }

    public function get_wc_logfiles_path( $handler ){
        $log_files_path  = WC_Log_Handler_File::get_log_file_path( $handler );
        return $log_files_path;
    }

    public function get_wc_logfiles_content( $handler ){
        $log_files_path  = WC_Log_Handler_File::get_log_file_path( $handler );
        $content         = esc_html( file_get_contents( $log_files_path ) );
        return $content;
    }
}
