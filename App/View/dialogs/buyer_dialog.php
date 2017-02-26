<div class="overlay" id="buyer-list">
	<div id="click-overlay" class="click-overlay"></div>
	<div class="dialog bottom" style="background: white; width: 890px; height: 275px; padding: 0;">
		<div class="top header-list-buyer">
			<h3 style="padding: 0 8px;">メーカーを選んでください</h3>
			<button class="button-create-buyer" onclick="onRegisterBuyer(); hideViewBuyerList()">新規登録</button>
			
		</div>
		<div id="content-list-buyer" class="content" style="height: 200px; overflow-y: overlay; overflow-x: hidden;">
			<table class="tb-register-buyer">
				<tbody>
					<tr style="margin-bottom: -1px">
						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-1" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('1', 'ドンキ殿')" class="item-buyer">ドンキ殿</button>
							<button class="action-buyer" onclick="editBuyer('1')">編集</button>
							<button class="action-buyer" onclick="deletedBuyer('1')">削除</button>
						</td>

						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-7" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('7', 'D社')" class="item-buyer">D社</button>
							<button class="action-buyer" onclick="onEditBuyer('7')">編集</button>
							<button class="action-buyer" onclick="onDeletedBuyer('7')">削除</button>
						</td>

					</tr>
					<tr style="margin-bottom: -1px">
						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-9" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('9', 'A社')" class="item-buyer">A社</button>
							<button class="action-buyer" onclick="editBuyer('9')">編集</button>
							<button class="action-buyer" onclick="deletedBuyer('9')">削除</button>
						</td>

						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-10" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('10', 'C社 ')" class="item-buyer">C社 </button>
							<button class="action-buyer" onclick="onEditBuyer('10')">編集</button>
							<button class="action-buyer" onclick="onDeletedBuyer('10')">削除</button>
						</td>

					</tr>
					<tr style="margin-bottom: -1px">
						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-20" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('20', 'B社')" class="item-buyer">B社</button>
							<button class="action-buyer" onclick="editBuyer('20')">編集</button>
							<button class="action-buyer" onclick="deletedBuyer('20')">削除</button>
						</td>

						<td style="width: 50%; border: 1px solid #ccc; padding: 5px;">
							<button id="item-buyer-23" style="width: 68%; border: none; padding: 0 0 0 5px; margin: 0; height: 100%;" onclick="choiseOneBuyer('23', 'ドン・キホーテ長崎屋')" class="item-buyer">ドン・キホーテ長崎屋</button>
							<button class="action-buyer" onclick="onEditBuyer('23')">編集</button>
							<button class="action-buyer" onclick="onDeletedBuyer('23')">削除</button>
						</td>

					</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>