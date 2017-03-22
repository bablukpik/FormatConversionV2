<div class="seller_list display_none" id="seller-list">
	<div class="dialog bottom" style="background: white; width: 600px; height: 275px; padding: 0;">
		<div class="top header-list-seller">
			<h3 style="padding: 10px 8px;">販売先を選んでください</h3>
			<button class="button-create-seller" onclick="onRegisterSeller();">新規登録</button>
			
		</div>
		<div id="content-list-seller" class="content" style="height: 200px; overflow-y: overlay; overflow-x: hidden;">
			<table id="table-seller-list">
				<tbody>
					<?php
						foreach ($seller as $each_seller) {
							echo "	<tr style='margin-bottom: -1px'>
										<td style='width: 50%; border: 1px solid #ccc; padding: 5px;'>
											<button style='width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;' class='item-buyer ".($each_seller['name']=='ドン・キホーテ'?'donQuixote':'')."' data-id='".$each_seller['id']."' data-name='".$each_seller['name']."' >".$each_seller['name']."</button>
											
											<button class='action-buyer'>編集</button>
											<button class='action-buyer'>削除</button>
										</td>
									</tr>";
						}
					?>
					

				</tbody>
			</table>
		</div>
	</div>
</div>