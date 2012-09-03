<?php if (@$gsExport == "") { ?>
			<!-- right column (end) -->
			<?php if (isset($gsTimer)) $gsTimer->Stop(); ?>
		</td></tr>
	</table>
	<!-- content (end) -->
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">
		<div class="ewFooterText">&nbsp;&copy;2010 RSUD dr. Soegiri</div>
		<!-- Place other links, for example, disclaimer, here -->
	</div>
	<!-- footer (end) -->	
</div>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email") { ?>
<script type="text/javascript">
<!--
xGetElementsByClassName(EWRPT_TABLE_CLASS, null, "TABLE", ewrpt_SetupTable); // init the table

//-->
</script>
<?php } ?>
</body>
</html>
