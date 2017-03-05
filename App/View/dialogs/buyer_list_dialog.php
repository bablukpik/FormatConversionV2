<div class="buyer_list" id="buyer-list">
	<div class="dialog bottom" style="background: white; width: 600px; height: 275px; padding: 0;">
		<div class="top header-list-buyer">
			<h3 style="padding: 10px 8px;">メーカーを選んでください</h3>
			<button class="button-create-buyer" onclick="onRegisterBuyer(); hideViewBuyerList()">新規登録</button>
			
		</div>
		<div id="content-list-buyer" class="content" style="height: 200px; overflow-y: overlay; overflow-x: hidden;">
			<table id="table-buyer-list">
				<tbody>
					<?php
						foreach ($buyer as $each_buyer) {
							echo "	<tr style='margin-bottom: -1px'>
										<td style='width: 50%; border: 1px solid #ccc; padding: 5px;'>
											<button style='width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;' class='item-buyer' data-id='".$each_buyer['id']."' data-name='".$each_buyer['name']."' >".$each_buyer['name']."</button>
											
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