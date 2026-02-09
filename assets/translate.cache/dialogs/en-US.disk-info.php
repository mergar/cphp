<dialog id="disk-info" class="window-box info-box" style="width:80%;height:80%;">
	<h1 class="tabs">
		<span><span id="trlt-70">Disk info</span>: <span id="di-disk-name"></span></span>
	</h1>
	<div class="window-content smpad">
		<div class="grid-wrapper">
			<div class="disk-list">
				<ul>
					<li class="sel">Disk 1: ...</li>
					<li>Disk 2: ...</li>
				</ul>
			</div>
			<div class="disk-info">
				<div class="tabs">
					<!-- <h2><span id="trlt-71">Virtual Machine Settings</span></h2> -->
					<div class="tabs_group">
						<span class="tab sel" id="tab-info">Information</span>
						<span class="tab" id="tab-smart">S.M.A.R.T.</span>
					</div>
				</div>
				<div id="tab-info-cnt" class="tab-cnt">Information of disk</div>
				<div id="tab-smart-cnt" class="tab-cnt hide pre"><pre>
					no data
				</pre></div>
			</div>
		</div>
	</div>
	<div class="buttons">
		<input type="button" value="Cancel" class="button red cancel-but">
	</div>
</dialog>