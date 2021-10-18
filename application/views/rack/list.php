<link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/table/style.css" />
<style>
table {
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
    max-width: 100%;
}
.table {
    margin-bottom: 18px;
    width: 100%;
}
.table th, .table td {
    border-top: 1px solid #DDDDDD;
    line-height: 18px;
    padding: 8px;
    text-align: left;
    vertical-align: top;
}
.table th {
    font-weight: bold;
}
.table thead th {
    vertical-align: bottom;
}
.table caption + thead tr:first-child th, .table caption + thead tr:first-child td, .table colgroup + thead tr:first-child th, .table colgroup + thead tr:first-child td, .table thead:first-child tr:first-child th, .table thead:first-child tr:first-child td {
    border-top: 0 none;
}
.table tbody + tbody {
    border-top: 2px solid #DDDDDD;
}
.table-condensed th, .table-condensed td {
    padding: 4px 5px;
}
.table-bordered {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-collapse: separate;
    border-color: #DDDDDD #DDDDDD #DDDDDD -moz-use-text-color;
    border-image: none;
    border-radius: 4px 4px 4px 4px;
    border-style: solid solid solid none;
    border-width: 1px 1px 1px 0;
}
.table-bordered th, .table-bordered td {
    border-left: 1px solid #DDDDDD;
}
.table-bordered caption + thead tr:first-child th, .table-bordered caption + tbody tr:first-child th, .table-bordered caption + tbody tr:first-child td, .table-bordered colgroup + thead tr:first-child th, .table-bordered colgroup + tbody tr:first-child th, .table-bordered colgroup + tbody tr:first-child td, .table-bordered thead:first-child tr:first-child th, .table-bordered tbody:first-child tr:first-child th, .table-bordered tbody:first-child tr:first-child td {
    border-top: 0 none;
}
.table-bordered thead:first-child tr:first-child th:first-child, .table-bordered tbody:first-child tr:first-child td:first-child {
    border-top-left-radius: 4px;
}
.table-bordered thead:first-child tr:first-child th:last-child, .table-bordered tbody:first-child tr:first-child td:last-child {
    border-top-right-radius: 4px;
}
.table-bordered thead:last-child tr:last-child th:first-child, .table-bordered tbody:last-child tr:last-child td:first-child {
    border-radius: 0 0 0 4px;
}
.table-bordered thead:last-child tr:last-child th:last-child, .table-bordered tbody:last-child tr:last-child td:last-child {
    border-bottom-right-radius: 4px;
}
.table-striped tbody tr:nth-child(2n+1) td, .table-striped tbody tr:nth-child(2n+1) th {
    background-color: #F9F9F9;
}
.table tbody tr:hover td, .table tbody tr:hover th {
    background-color: #F5F5F5;
}
</style>
<div id="content">
    <h1><?php echo $title_info;?></h1>

    <div id="content_padded">
	   
	   <div style="color:red;font-size:14px"><?php echo isset($error)?$error:""; ?></div>
	   
       <table width="98%" cellspacing="2" cellpadding="3" border="0" class="form">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Please choose file to upload.</td>
                                <td class="fieldarea">
									<?php echo form_open_multipart('rack/upload'); ?>
									<input type="file" name="userfile" size="45" />  
									&nbsp;&nbsp;(Please note that it will be override by your action)
								</td>
								<td>
									<div align="center"><input type="submit" value="upload" /></div>
								</td>
                            </tr>
                        </tbody>
            </table>
			
			<br/>
			<br/>
			
			<div align="center">
			<table class="table table-bordered table-striped" style="width: 98%;align:center;" align="center">
            <tbody>
                <tr>
                    <th style="width:20%">Uploaded By user</th>
                    <th style="width:50%">Upload time</th>
                    <th>Download</th>
                </tr>
				
				<?php
					$i = 1;
					foreach ($data as $item) {
						echo "<tr>";
						echo "<td>{$item->username}</td>";
						echo "<td>{$item->uploadtime}</td>";
						if($i == 1)
							echo "<td><a href='",site_url('resources/rack/'),"/",$item->filename,"'>download</a></td>";
						else
							echo "<td></td>";
						echo "</tr>";
						$i++;
					}
				?>
			</tbody>
        </table>
    </div>
        </div>
		
    </div>
	
</div>