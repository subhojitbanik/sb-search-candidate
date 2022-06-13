<?php
include_once("vendor/autoload.php");

// Get the API client and construct the service object.

$client = new Google_Client();

$client->setApplicationName('app');

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

$client->setAuthConfig(get_template_directory() . '/credentials.json');

$client->setAccessType('offline');

$service = new Google_Service_Sheets($client);

$spreadsheetId = '1Bn5gYVDGIVqelsCMaDWpHLHLoyeMHTtk5ByjvNVygzg';

$range = 'All Candidates';

$response = $service->spreadsheets_values->get($spreadsheetId, $range);

$values = $response->getValues();

function virtual_ballot_fn()
{
    global $values;
?>
    <div class="container">
        <div class="row my-3">
            <div class="col-md-6 col-sm-12">
                <form action="" method="post">
                    <?php
                    $uss_input = '0';
                    $uss_cat = 'US Senator';
                    $sos_cat = 'Secretary of State';
                    $uss_data = search_data($values, 19, $uss_input);
                    $uss = search_data($uss_data, 13, $uss_cat);
                    $sos = search_data($uss_data, 13, $sos_cat);
                    // echo '<pre>';
                    // print_r($uss);
                    foreach ($uss as $us_val) {
                        $arr_uss[] = $us_val[13];
                    }
                    foreach ($sos as $sos_val) {
                        $arr_sos[] = $sos_val[13];
                    }
                    if (!empty($_GET['cd'])) {
                        $cd_input = $_GET['cd'];
                    }
                    $cd_list = search_data($values, 19, $cd_input);
                    $cd = search_data($cd_list, 13, 'US Representative');
                    foreach ($cd as $val) {
                        $arrcd[] = $val[13];
                    }
                    //$arr = array_values(array_unique($arr));
                    if (!empty($_GET['sd'])) {
                        $sd_input = $_GET['sd'];
                    }
                    $sd_list = search_data($values, 20, $sd_input);
                    $sd = search_data($sd_list, 13, 'State Senator');
                    foreach ($sd as $val) {
                        $arrsd[] = $val[13];
                    }
                    if (!empty($_GET['hd'])) {
                        $hd_input = $_GET['hd'];
                    }
                    $hd_list = search_data($values, 21, $hd_input);
                    $hd = search_data($hd_list, 13, 'State Representative');
                    foreach ($hd as $val) {
                        $arrhd[] = $val[13];
                    }
                    if (!empty($_GET['cb'])) {
                        $cb_input = $_GET['cb'];
                    }
                    $cb = search_data($values, 22, $cb_input);
                    foreach ($cb as $val) {
                        $arrcb[] = $val[13];
                    }
                    $cb_pa = search_data($cb, 13, 'Prosecuting Attorney');
                    $cb_ca = search_data($cb, 13, 'County Assessor');
                    $cb_cs = search_data($cb, 13, 'County Sheriff');
                    $cb_cau = search_data($cb, 13, 'County Auditor');
                    $cb_cc = search_data($cb, 13, 'County Commissioner');
                    $cb_csur = search_data($cb, 13, 'County Surveyor');
                    $cb_ct = search_data($cb, 13, 'County Treasurer');
                    $cb_cco = search_data($cb, 13, 'County Coroner');
                    $cb_cr = search_data($cb, 13, 'County Recorder');
                    $cb_jcc = search_data($cb, 13, 'Judge, Circuit Court');
                    $cb_jsmc = search_data($cb, 13, 'Judge, Small Claims Court');
                    $cb_jsc = search_data($cb, 13, 'Judge, Superior Court');
                    $arr[] = array_merge(array_unique((array)$arr_uss), array_unique((array)$arr_sos), array_unique((array)$arrcd), array_unique((array)$arrsd), array_unique((array)$arrhd), array_unique((array)$arrcb));
                    // echo'<pre>';
                    // print_r($arrcb);
                    ?>
                    <label for="ofc_cat">Select Office Category</label>
                    <select name="offc_category" id="ofc_cat" class="form-control">
                        <option value="show_hide">Show All</option>
                        <?php
                        foreach ($arr as $val) {
                            for ($i = 0; $i < sizeof($val); $i++) {
                                $sec_id = str_replace(' ', '_', $val[$i]);
                                $sec_id = str_replace(',', '_', $sec_id);
                        ?>
                                <option value="<?php echo $sec_id; ?>"><?php echo $val[$i]; ?></option>
                        <?php }
                        } ?>
                    </select>

                </form>
            </div>
            <div class="col-md-6 col-sm-12"></div>
        </div>

        <!-- US Senator starts -->
        <div class="row my-3 show_hide <?php print(str_replace(' ', '_', $arr_uss[0])); ?>">
            <?php echo '<h2 style="text-align:left;"> ' . $arr_uss[0] . ' </h2> <hr>'; ?>
            <div class="row uss">
                <?php foreach ($uss as $fd) { ?>
                    <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                echo 'incum1';
                                            } ?>">
                        <div class="card mb-3">
                            <?php if ($fd[26] == 'active') { ?>
                                <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                            <?php } else { ?>
                                <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                            <?php } ?>
                            <div class="card-body">
                                <div class="circular--portrait">
                                    <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                </div>
                                <h3 class="card-title"><?php if ($fd[15]) {
                                                            echo $fd[15];
                                                        } ?></h3>
                                <?php if ($fd[16]) { ?>
                                    <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                <?php } ?>
                                <hr class="hrline">
                                <div class="row">
                                    <div class="col-sm">
                                        <p class="card-text">Ballot Order </p>
                                        <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                        <p class="card-text">Office</p>
                                        <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                    ?></h4> -->
                                        <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                    </div>
                                    <div class="col-sm">
                                        <p class="card-text">Terms End</p>
                                        <h4 class="card-title">2022</h4>
                                        <p class="card-text">Last Elected</p>
                                        <h4 class="card-title">2022</h4>
                                    </div>
                                </div>
                                <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- US Senator ends -->
        <!-- Secretary of State starts -->
        <div class="row my-3 show_hide <?php print(str_replace(' ', '_', $arr_sos[0])); ?>">
            <?php echo '<h2 style="text-align:left;"> ' . $arr_sos[0] . ' </h2> <hr>'; ?>
            <div class="row sos">
                <?php foreach ($sos as $fd) { ?>
                    <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                echo 'incum2';
                                            } ?>">
                        <div class="card mb-3">
                            <?php if ($fd[26] == 'active') { ?>
                                <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                            <?php } else { ?>
                                <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                            <?php } ?>
                            <div class="card-body">
                                <div class="circular--portrait">
                                    <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                </div>
                                <h3 class="card-title"><?php if ($fd[15]) {
                                                            echo $fd[15];
                                                        } ?></h3>
                                <?php if ($fd[16]) { ?>
                                    <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                <?php } ?>
                                <hr class="hrline">
                                <div class="row">
                                    <div class="col-sm">
                                        <p class="card-text">Ballot Order </p>
                                        <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                        <p class="card-text">Office</p>
                                        <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                    </div>
                                    <div class="col-sm">
                                        <p class="card-text">Terms End</p>
                                        <h4 class="card-title">2022</h4>
                                        <p class="card-text">Last Elected</p>
                                        <h4 class="card-title">2022</h4>
                                    </div>
                                </div>
                                <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Secretary of State ends -->
        <!-- US Representative starts -->
        <?php if (in_array('US Representative', (array)$arrcd)) { ?>
            <div class="row my-3 show_hide <?php print(str_replace(' ', '_', $arrcd[0])); ?>">
                <?php echo '<h2 style="text-align:left;"> ' . $arrcd[0] . ' </h2> <hr>'; ?>
                <div class="row us_representativee">
                    <?php foreach ($cd as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum3';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- US Representative ends -->
        <!-- State Senator starts -->
        <?php if (in_array('State Senator', (array)$arrsd)) { ?>
            <div class="row my-3 show_hide <?php print(str_replace(' ', '_', $arrsd[0])); ?>">
                <?php echo '<h2 style="text-align:left;"> ' . $arrsd[0] . '</h2> <hr>'; ?>
                <div class="row sb_state_senator">
                    <?php foreach ($sd as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum4';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- State Senator ends -->
        <!-- house district starts -->
        <?php if (in_array('State Representative', (array)$arrhd)) { ?>
            <div class="row show_hide <?php print(str_replace(' ', '_', $arrhd[0])); ?>">
                <?php echo '<h2 style="text-align:left;"> ' . $arrhd[0] . ' </h2> <hr>'; ?>
                <div class="row sb_house_dist">
                    <?php foreach ($hd as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum5';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- house district ends -->
        <!-- County Boundaries prosecuting Attorney starts -->
        <?php if (in_array('Prosecuting Attorney', (array)$arrcb)) { ?>
            <div class="row show_hide <?php print(str_replace(' ', '_', $arrcb[0])); ?>">
                <?php echo '<h2 style="text-align:left;"> Prosecuting Attorney </h2> <hr>'; ?>
                <div class="row sb_pros_attorn">
                    <?php foreach ($cb_pa as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum6';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries prosecuting Attorney ends -->
        <!-- County Boundaries County Assessor starts -->
        <?php if (in_array('County Assessor', (array)$arrcb)) { ?>
            <div class="row show_hide County_Assessor my-3">
                <?php echo '<h2 style="text-align:left;"> County Assessor </h2> <hr>'; ?>
                <div class="row sb_cb_ca">
                    <?php foreach ($cb_ca as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum7';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Assessor ends -->
        <!-- County Boundaries County Sheriff starts -->
        <?php if (in_array('County Sheriff', (array)$arrcb)) { ?>
            <div class="row show_hide County_Sheriff my-3">
                <?php echo '<h2 style="text-align:left;"> County Sheriff </h2> <hr>'; ?>
                <div class="row sb_cb_cs">
                    <?php foreach ($cb_cs as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum8';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Sheriff ends -->
        <!-- County Boundaries County Auditor starts -->
        <?php if (in_array('County Auditor', (array)$arrcb)) { ?>
            <div class="row show_hide County_Auditor my-3">
                <?php echo '<h2 style="text-align:left;"> County Auditor </h2> <hr>'; ?>
                <div class="row sb_cb_cau">
                    <?php foreach ($cb_cau as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum9';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Auditor ends -->
        <!-- County Boundaries County Commissioner starts -->
        <?php if (in_array('County Commissioner', (array)$arrcb)) { ?>
            <div class="row show_hide County_Commissioner my-3">
                <?php echo '<h2 style="text-align:left;"> County Commissioner </h2> <hr>'; ?>
                <div class="row sb_cb_cc">
                    <?php foreach ($cb_cc as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum10';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Commissioner ends -->
        <!-- County Boundaries County Surveyor starts -->
        <?php if (in_array('County Surveyor', (array)$arrcb)) { ?>
            <div class="row show_hide County_Surveyor my-3">
                <?php echo '<h2 style="text-align:left;"> County Surveyor </h2> <hr>'; ?>
                <div class="row sb_cb_csur">
                    <?php foreach ($cb_csur as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum11';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Surveyor ends -->
        <!-- County Boundaries County Treasurer starts -->
        <?php if (in_array('County Treasurer', (array)$arrcb)) { ?>
            <div class="row show_hide County_Treasurer my-3">
                <?php echo '<h2 style="text-align:left;"> County Treasurer </h2> <hr>' ?>;
                <div class="row sb_cb_ct">
                    <?php foreach ($cb_ct as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum12';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Treasurer ends -->
        <!-- County Boundaries County Coroner starts -->
        <?php if (in_array('County Coroner', (array)$arrcb)) { ?>
            <div class="row show_hide County_Coroner my-3">
                <?php echo '<h2 style="text-align:left;"> County Coroner </h2> <hr>'; ?>
                <div class="row sb_cb_cco">
                    <?php foreach ($cb_cco as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum13';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Coroner ends -->
        <!-- County Boundaries County Recorder starts -->
        <?php if (in_array('County Recorder', (array)$arrcb)) { ?>
            <div class="row show_hide County_Recorder my-3">
                <?php echo '<h2 style="text-align:left;"> County Recorder </h2> <hr>'; ?>
                <div class="row sb_cb_cr">
                    <?php foreach ($cb_cr as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum14';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries County Recorder ends -->
        <!-- County Boundaries Judge, Circuit Court starts -->
        <?php if (in_array('Judge, Circuit Court', (array)$arrcb)) { ?>
            <div class="row show_hide Judge__Circuit_Court my-3">
                <?php echo '<h2 style="text-align:left;"> Judge, Circuit Court </h2> <hr>'; ?>
                <div class="row sb_cb_jcc">
                    <?php foreach ($cb_jcc as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum15';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries Judge, Circuit Court ends -->
        <!-- County Boundaries Judge, Small Claims Court starts -->
        <?php if (in_array('Judge, Small Claims Court', (array)$arrcb)) { ?>
            <div class="row show_hide Judge__Small_Claims_Court">
                <?php echo '<h2 style="text-align:left;"> Judge, Small Claims Court </h2> <hr>'; ?>
                <div class="row sb_cb_jsmc">
                    <?php foreach ($cb_jsmc as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum16';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries Judge, Small Claims Court ends -->
        <!-- County Boundaries Judge, Superior Court starts -->
        <?php if (in_array('Judge, Superior Court', (array)$arrcb)) { ?>
            <div class="row show_hide Judge__Superior_Court">
                <?php echo '<h2 style="text-align:left;"> Judge, Superior Court </h2> <hr>'; ?>
                <div class="row sb_cb_jsc">
                    <?php foreach ($cb_jsc as $fd) { ?>
                        <div class="col-md-4 <?php if ($fd[26] == 'active') {
                                                    echo 'incum17';
                                                } ?>">
                            <div class="card mb-3">
                                <?php if ($fd[26] == 'active') { ?>
                                    <div class="card-header1 incumbent <?php echo strtolower($fd[16]); ?>">Incumbent</div>
                                <?php } else { ?>
                                    <div class="card-header1 <?php echo strtolower($fd[16]); ?>">Challenger</div>
                                <?php } ?>
                                <div class="card-body">
                                    <div class="circular--portrait">
                                        <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                    </div>
                                    <h3 class="card-title"><?php if ($fd[15]) {
                                                                echo $fd[15];
                                                            } ?></h3>
                                    <?php if ($fd[16]) { ?>
                                        <button type="button" class="<?php echo $fd[16]; ?> btn btn-sm cntr"><?php echo $fd[16]; ?> </button>
                                    <?php } ?>
                                    <hr class="hrline">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">Ballot Order </p>
                                            <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                            <p class="card-text">Office</p>
                                            <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                        ?></h4> -->
                                            <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                        </div>
                                        <div class="col-sm">
                                            <p class="card-text">Terms End</p>
                                            <h4 class="card-title">2022</h4>
                                            <p class="card-text">Last Elected</p>
                                            <h4 class="card-title">2022</h4>
                                        </div>
                                    </div>
                                    <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <!-- County Boundaries Judge, Superior Court ends -->
    </div>

<?php } ?>