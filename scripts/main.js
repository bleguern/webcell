/* BASIC FUNCTIONS */

function clearHtmlSelect(htmlSelect)
{
	htmlSelect.options.length = 0
	htmlSelect.options[0] = new Option(' ', '')
}

function checkEmail(email) {

	if (email != '') {
		var at = '@'
		var dot = '.'

		var lat = email.indexOf(at)
		var lstr = email.length
		var ldot = email.indexOf(dot)

		if (email.indexOf(at) == -1 || email.indexOf(at) == 0 || email.indexOf(at) == lstr) {
			return false;
		}

		if (email.indexOf(dot) == -1 || email.indexOf(dot) == 0 || email.indexOf(dot) == lstr) {
			return false;
		}

	    if (email.indexOf(" ") != -1) {
	    	return false;
	 	}
	}

	return true;
}


function updateCount()
{
	if (parent.window.document.forms[0].count != null)
	{
		parent.window.document.forms[0].count.value = "(" + window.document.forms[0].count.value + ")"
	}
	
	if (parent.window.document.forms[0].count2 != null)
	{
		parent.window.document.forms[0].count2.value = "(" + window.document.forms[0].count2.value + ")"
	}
}

function goto(source, url, id)
{
	window.parent.location=url+'?history='+source.parent.location.href+'&id='+id;
}





/********************************************************************************************************/



/*
	Site forms
*/

function siteFormOnSiteChange() {
	siteForm.site_id.value = siteForm.site_select.value
	siteForm.site_name.value = siteForm.site_select.options[siteForm.site_select.selectedIndex].text
}

function siteFormFill() {
	
	var siteSelect, site 
	
	site = siteForm.site_id.value
	siteSelect = siteForm.site_select
	
	clearHtmlSelect(siteSelect)

	for (i = 0, j = 1; i < siteTable.length; i++) {
		siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
		
		if (site == siteTable[i][0]) {
			siteSelect.options[j].selected = true
		}
		
		j++
	}
}

/*
	Admin site forms
*/
function updateSiteFormOnCellChange() {
	updateSiteForm.cell.value = updateSiteForm.cell_select.value
}


/*
	Admin cell forms
*/

function addCellFormFill() {
	
	var siteSelect, site 
	
	site = addCellForm.site.value
	siteSelect = addCellForm.site_select
	
	if (siteSelect) {
		clearHtmlSelect(siteSelect)
	
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
}

function addCellFormOnSiteChange() {
	addCellForm.site.value = addCellForm.site_select.value
}

function updateCellFormFill() {
	
	var siteSelect, site 
	
	site = updateCellForm.site.value
	siteSelect = updateCellForm.site_select
	
	if (siteSelect) {
		clearHtmlSelect(siteSelect)
	
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
}

function updateCellFormOnSiteChange() {
	updateCellForm.site.value = updateCellForm.site_select.value
}

function updateCellFormOnMachineChange() {
	updateCellForm.machine.value = updateCellForm.machine_select.value
}

/*
	Admin machine forms
*/

function addMachineFormFill() {
	
	var siteSelect, cellSelect, mainMachineSelect, site, cell, mainMachine
	
	site = addMachineForm.site.value
	cell = addMachineForm.cell.value
	mainMachine = addMachineForm.main_machine.value
	siteSelect = addMachineForm.site_select
	cellSelect = addMachineForm.cell_select
	mainMachineSelect = addMachineForm.main_machine_select

	if (siteSelect) {
		clearHtmlSelect(siteSelect)

		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	clearHtmlSelect(cellSelect)
	
	for (i = 0, j = 1; i < cellTable.length; i++) {
		
		if ((cellTable[i][2] == site) || (site == '')) {
			cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
		
			if (cell == cellTable[i][0]) {
				cellSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	clearHtmlSelect(mainMachineSelect)
	
	for (i = 0, j = 1; i < mainMachineTable.length; i++) {
		
		mainMachineSelect.options[j] = new Option(mainMachineTable[i][1], mainMachineTable[i][0])
			
		if (mainMachine == mainMachineTable[i][0]) {
			mainMachineSelect.options[j].selected = true
		}
		
		j++
	}
}

function addMachineFormOnSiteChange() {
	
	var site, siteSelect, cellSelect
	
	site = addMachineForm.site_select.value
	addMachineForm.site.value = site
	
	cellSelect = addMachineForm.cell_select
	
	clearHtmlSelect(cellSelect)

	addMachineForm.cell.value = ''

	for (i = 0, j = 1; i < cellTable.length; i++) {
		if ((cellTable[i][2] == site) || (site == '')) {
			cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
			j++
		}
	}
}

function addMachineFormOnCellChange() {
	
	var site, cell, siteSelect, cellSelect
	
	cell = addMachineForm.cell_select.value
	addMachineForm.cell.value = cell
	
	siteSelect = addMachineForm.site_select

	siteSelect.selectedIndex = 0
	addMachineForm.site.value = ''
	
	for (i = 0; i < cellTable.length; i++) {
		if (cellTable[i][0] == cell) {
			site = cellTable[i][2]
			addMachineForm.site.value = site
			break;
		}
	}
	
	for (i = 0; i < siteSelect.options.length; i++) {
		if (siteSelect.options[i].value == site) {
			siteSelect.options[i].selected = true
		}
	}
}

function addMachineFormOnMainMachineChange() {
	
	addMachineForm.main_machine.value = addMachineForm.main_machine_select.value
}

function updateMachineFormFill() {
	
	var siteSelect, cellSelect, mainMachineSelect, site, cell, mainMachine
	
	cell = updateMachineForm.cell.value
	site = updateMachineForm.site.value
	mainMachine = updateMachineForm.main_machine.value
	siteSelect = updateMachineForm.site_select
	cellSelect = updateMachineForm.cell_select
	mainMachineSelect = updateMachineForm.main_machine_select
	
	if (siteSelect) {
		clearHtmlSelect(siteSelect)
		
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
		
	clearHtmlSelect(cellSelect)
	
	for (i = 0, j = 1; i < cellTable.length; i++) {
		if ((cellTable[i][2] == site) || (site == '')) {
			cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
			
			if (cell == cellTable[i][0]) {
				cellSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	clearHtmlSelect(mainMachineSelect)
	
	for (i = 0, j = 1; i < mainMachineTable.length; i++) {
		
		mainMachineSelect.options[j] = new Option(mainMachineTable[i][1], mainMachineTable[i][0])
			
		if (mainMachine == mainMachineTable[i][0]) {
			mainMachineSelect.options[j].selected = true
		}
		
		j++
	}
}

function updateMachineFormOnSiteChange() {
	
	var site, siteSelect, cellSelect
	
	site = updateMachineForm.site_select.value
	updateMachineForm.site.value = site
	
	cellSelect = updateMachineForm.cell_select
	
	clearHtmlSelect(cellSelect)

	updateMachineForm.cell.value = ''

	for (i = 0, j = 1; i < cellTable.length; i++) {
		if ((cellTable[i][2] == site) || (site == '')) {
			cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
			j++
		}
	}
}

function updateMachineFormOnCellChange() {
	
	var site, cell, siteSelect, cellSelect
	
	cell = updateMachineForm.cell_select.value
	updateMachineForm.cell.value = cell
	
	siteSelect = updateMachineForm.site_select

	siteSelect.selectedIndex = 0
	updateMachineForm.site.value = ''
	
	for (i = 0; i < cellTable.length; i++) {
		if (cellTable[i][0] == cell) {
			site = cellTable[i][2]
			updateMachineForm.site.value = site
			break;
		}
	}
	
	for (i = 0; i < siteSelect.options.length; i++) {
		if (siteSelect.options[i].value == site) {
			siteSelect.options[i].selected = true
		}
	}
}

function updateMachineFormOnMainMachineChange() {
	
	updateMachineForm.main_machine.value = updateMachineForm.main_machine_select.value
}


function updateMachineFormOnProductChange() {
	
	updateMachineForm.product.value = updateMachineForm.product_select.value
}

/*
	Admin role forms
*/
function updateRoleFormOnUserChange() {
	updateRoleForm.user.value = updateRoleForm.user_select.value
}

/*
	Admin access forms
*/
function updateAccessFormOnRoleChange() {
	updateAccessForm.role.value = updateAccessForm.role_select.value
}


/*
	Admin user forms
*/
function addUserFormFill() {
	
	var siteSelect, roleSelect, site, role 
	
	site = addUserForm.site.value
	role = addUserForm.role.value
	siteSelect = addUserForm.site_select
	roleSelect = addUserForm.role_select
	
	clearHtmlSelect(siteSelect)
	clearHtmlSelect(roleSelect)

	for (i = 0, j = 1; i < siteTable.length; i++) {
		siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
		
		if (site == siteTable[i][0]) {
			siteSelect.options[j].selected = true
		}
		
		j++
	}
	
	for (i = 0, j = 1; i < roleTable.length; i++) {
		roleSelect.options[j] = new Option(roleTable[i][1], roleTable[i][0])
		
		if (role == roleTable[i][0]) {
			roleSelect.options[j].selected = true
		}
		
		j++
	}
}

function addUserFormOnSiteChange() {
	addUserForm.site.value = addUserForm.site_select.value
}

function addUserFormOnRoleChange() {
	addUserForm.role.value = addUserForm.role_select.value
}

function updateUserFormFill() {
	
	var siteSelect, roleSelect, site, role 
	
	site = updateUserForm.site.value
	role = updateUserForm.role.value
	siteSelect = updateUserForm.site_select
	roleSelect = updateUserForm.role_select
	
	clearHtmlSelect(siteSelect)
	clearHtmlSelect(roleSelect)

	for (i = 0, j = 1; i < siteTable.length; i++) {
		siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
		
		if (site == siteTable[i][0]) {
			siteSelect.options[j].selected = true
		}
		
		j++
	}
	
	for (i = 0, j = 1; i < roleTable.length; i++) {
		roleSelect.options[j] = new Option(roleTable[i][1], roleTable[i][0])
		
		if (role == roleTable[i][0]) {
			roleSelect.options[j].selected = true
		}
		
		j++
	}
}

function updateUserFormOnSiteChange() {
	updateUserForm.site.value = updateUserForm.site_select.value
}

function updateUserFormOnRoleChange() {
	updateUserForm.role.value = updateUserForm.role_select.value
}



/*
	Sales admin
*/


function addSalesAdminFormFill() {
	
	var userSelect, user 
	
	user = addSalesAdminForm.user.value
	userSelect = addSalesAdminForm.user_select
	
	clearHtmlSelect(userSelect)

	for (i = 0, j = 1; i < userTable.length; i++) {
		userSelect.options[j] = new Option(userTable[i][1], userTable[i][0])
		
		if (user == userTable[i][0]) {
			userSelect.options[j].selected = true
		}
		
		j++
	}
}

function addSalesAdminFormOnUserChange() {
	addSalesAdminForm.user.value = addSalesAdminForm.user_select.value
}

function updateSalesAdminFormFill() {
	
	var userSelect, user 
	
	user = updateSalesAdminForm.user.value
	userSelect = updateSalesAdminForm.user_select
	
	clearHtmlSelect(userSelect)

	for (i = 0, j = 1; i < userTable.length; i++) {
		userSelect.options[j] = new Option(userTable[i][1], userTable[i][0])
		
		if (user == userTable[i][0]) {
			userSelect.options[j].selected = true
		}
		
		j++
	}
}

function updateSalesAdminFormOnUserChange() {
	updateSalesAdminForm.user.value = updateSalesAdminForm.user_select.value
}

/*
	Product information form
*/
function productInformationFormFill() {
	
	var productSelect, product 
	
	product = productInformationForm.product.value
	productSelect = productInformationForm.product_select
	
	clearHtmlSelect(productSelect)

	for (i = 0, j = 1; i < productTable.length; i++) {
		productSelect.options[j] = new Option(productTable[i][1], productTable[i][0])
		
		if (product == productTable[i][0]) {
			productSelect.options[j].selected = true
		}
		
		j++
	}
}

function productInformationFormOnProductChange() {
	productInformationForm.product.value = productInformationForm.product_select.value
	productInformationForm.submit();
}


/*
	Customer information form
*/
function customerInformationFormFill() {
	
	var customerSelect, customer 
	
	customer = customerInformationForm.customer.value
	customerSelect = customerInformationForm.customer_select
	
	clearHtmlSelect(customerSelect)

	for (i = 0, j = 1; i < customerTable.length; i++) {
		customerSelect.options[j] = new Option(customerTable[i][1], customerTable[i][0])
		
		if (customer == customerTable[i][0]) {
			customerSelect.options[j].selected = true
		}
		
		j++
	}
}

function customerInformationFormOnCustomerChange() {
	customerInformationForm.customer.value = customerInformationForm.customer_select.value
	customerInformationForm.submit();
}

/*
	Custords information form
*/
function custordsInformationFormFill() {
	
	var custordsSelect, custords 
	
	custords = custordsInformationForm.custords.value
	custordsSelect = custordsInformationForm.custords_select
	
	clearHtmlSelect(custordsSelect)

	for (i = 0, j = 1; i < custordsTable.length; i++) {
		custordsSelect.options[j] = new Option(custordsTable[i][1], custordsTable[i][0])
		
		if (custords == custordsTable[i][0]) {
			custordsSelect.options[j].selected = true
		}
		
		j++
	}
}

function custordsInformationFormOnCustordsChange() {
	custordsInformationForm.custords.value = custordsInformationForm.custords_select.value
	custordsInformationForm.submit();
}

/*
	Cell information form
*/
function cellInformationFormFill() {
	
	var cellSelect, cell 
	
	cell = cellInformationForm.cell.value
	cellSelect = cellInformationForm.cell_select
	
	clearHtmlSelect(cellSelect)

	for (i = 0, j = 1; i < cellTable.length; i++) {
		cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
		
		if (cell == cellTable[i][0]) {
			cellSelect.options[j].selected = true
		}
		
		j++
	}
}

function cellInformationFormOnCellChange() {
	cellInformationForm.cell.value = cellInformationForm.cell_select.value
	cellInformationForm.submit();
}

/*
	Machine information form
*/
function machineInformationFormFill() {
	
	var machineSelect, machine 
	
	machine = machineInformationForm.machine.value
	machineSelect = machineInformationForm.machine_select
	
	clearHtmlSelect(machineSelect)

	for (i = 0, j = 1; i < machineTable.length; i++) {
		machineSelect.options[j] = new Option(machineTable[i][1], machineTable[i][0])
		
		if (machine == machineTable[i][0]) {
			machineSelect.options[j].selected = true
		}
		
		j++
	}
}

function machineInformationFormOnMachineChange() {
	machineInformationForm.machine.value = machineInformationForm.machine_select.value
	machineInformationForm.submit();
}


/*
	Add message form
*/
function addMessageFormFill() {
	
	var siteSelect, site 
	
	site = addMessageForm.site.value
	siteSelect = addMessageForm.site_select
	
	if (siteSelect) {
		clearHtmlSelect(siteSelect)
	
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
}

function addMessageFormOnSiteChange() {
	addMessageForm.site.value = addMessageForm.site_select.value
}

/*
	Update message form
*/
function updateMessageFormFill() {
	
	var siteSelect, site 
	
	site = updateMessageForm.site.value
	siteSelect = updateMessageForm.site_select
	
	if (siteSelect) {
		clearHtmlSelect(siteSelect)
	
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
}

function updateMessageFormOnSiteChange() {
	updateMessageForm.site.value = updateMessageForm.site_select.value
}



/*
	Planning board cell form
*/
function planningBoardCellFormFill() {
	
	var cellSelect, cell 
	
	cell = planningBoardCellForm.id.value
	cellSelect = planningBoardCellForm.cell_select
	
	clearHtmlSelect(cellSelect)

	for (i = 0, j = 1; i < cellTable.length; i++) {
		cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
		
		if (cell == cellTable[i][0]) {
			cellSelect.options[j].selected = true
		}
		
		j++
	}
}

function planningBoardCellFormOnCellChange() {
	planningBoardCellForm.id.value = planningBoardCellForm.cell_select.value
	planningBoardCellForm.cell_name.value = planningBoardCellForm.cell_select.options[planningBoardCellForm.cell_select.selectedIndex].text
	planningBoardCellForm.submit();
}

/*
	Out of stock
*/
function outOfStockFormFill() {
	
	var warehouseSelect, warehouse 
	
	warehouse = outOfStockForm.warehouse.value
	warehouseSelect = outOfStockForm.warehouse_select
	
	clearHtmlSelect(warehouseSelect)

	for (i = 0, j = 1; i < warehouseTable.length; i++) {
		warehouseSelect.options[j] = new Option(warehouseTable[i][1], warehouseTable[i][0])
		
		if (warehouse == warehouseTable[i][0]) {
			warehouseSelect.options[j].selected = true
		}
		
		j++
	}
}

function outOfStockFormOnWarehouseChange() {
	outOfStockForm.warehouse.value = outOfStockForm.warehouse_select.value
	outOfStockForm.warehouse_name.value = outOfStockForm.warehouse_select.options[outOfStockForm.warehouse_select.selectedIndex].text
	outOfStockForm.submit();
}



/*
	Planning board machine form
*/
function planningBoardMachineFormFill() {
	
	var cellSelect, machineSelect, cell, machine
	
	cell = planningBoardMachineForm.cell.value
	cellSelect = planningBoardMachineForm.cell_select
	machine = planningBoardMachineForm.id.value
	machineSelect = planningBoardMachineForm.machine_select
	
	clearHtmlSelect(cellSelect)
	clearHtmlSelect(machineSelect)
	
	for (i = 0, j = 1; i < machineTable.length; i++) {
		
		if (machineTable[i][0] == machine) {
			cell = machineTable[i][2]
			break
		}
	}
	
	for (i = 0, j = 1; i < machineTable.length; i++) {
		
		if ((cell == machineTable[i][2]) || cell == '') {
			machineSelect.options[j] = new Option(machineTable[i][1], machineTable[i][0])
		
			if (machine == machineTable[i][0]) {
				machineSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	for (i = 0, j = 1; i < cellTable.length; i++) {
		cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
		
		if (cell == cellTable[i][0]) {
			cellSelect.options[j].selected = true
		}
		
		j++
	}
}

function planningBoardMachineFormOnCellChange() {
	var cellSelect, machineSelect, cell
	
	cellSelect = planningBoardMachineForm.cell_select
	machineSelect = planningBoardMachineForm.machine_select
	
	cell = planningBoardMachineForm.cell_select.value
	
	planningBoardMachineForm.cell.value = cell
	
	clearHtmlSelect(machineSelect)
	
	for (i = 0, j = 1; i < machineTable.length; i++) {
		
		if ((cell == machineTable[i][2]) || cell == '') {
			machineSelect.options[j] = new Option(machineTable[i][1], machineTable[i][0])
			j++
		}
	}
}

function planningBoardMachineFormOnMachineChange() {
	planningBoardMachineForm.id.value = planningBoardMachineForm.machine_select.value
	planningBoardMachineForm.machine_name.value = planningBoardMachineForm.machine_select.options[planningBoardMachineForm.machine_select.selectedIndex].text
	planningBoardMachineForm.submit();
}


/*
	Add site form
*/
function addSiteFormFill() {
	
	var warehouseSelect, warehouse
	
	warehouse = addSiteForm.warehouse.value
	warehouseSelect = addSiteForm.warehouse_select
	
	clearHtmlSelect(warehouseSelect)

	for (i = 0, j = 1; i < warehouseTable.length; i++) {
		warehouseSelect.options[j] = new Option(warehouseTable[i][1], warehouseTable[i][0])
		
		if (warehouse == warehouseTable[i][0]) {
			warehouseSelect.options[j].selected = true
		}
		
		j++
	}
}

function addSiteFormOnwarehouseChange() {
	addSiteForm.warehouse.value = addSiteForm.warehouse_select.value
}

/*
	Update site form
*/
function updateSiteFormFill() {
	
	var warehouseSelect, warehouse
	
	warehouse = updateSiteForm.warehouse.value
	warehouseSelect = updateSiteForm.warehouse_select
	
	clearHtmlSelect(warehouseSelect)

	for (i = 0, j = 1; i < warehouseTable.length; i++) {
		warehouseSelect.options[j] = new Option(warehouseTable[i][1], warehouseTable[i][0])
		
		if (warehouse == warehouseTable[i][0]) {
			warehouseSelect.options[j].selected = true
		}
		
		j++
	}
}

function updateSiteFormOnwarehouseChange() {
	updateSiteForm.warehouse.value = updateSiteForm.warehouse_select.value
}





/*
	To produce form
*/
function toProduceFormFill() {
	
	var siteSelect, cellSelect, machineSelect, site, cell, machine
	
	site = toProduceForm.site.value
	siteSelect = toProduceForm.site_select
	cell = toProduceForm.cell.value
	cellSelect = toProduceForm.cell_select
	machine = toProduceForm.machine.value
	machineSelect = toProduceForm.machine_select
	
	if (siteSelect != null)
	{
		clearHtmlSelect(siteSelect)
	}
	
	clearHtmlSelect(cellSelect)
	clearHtmlSelect(machineSelect)
	
	for (i = 0; i < machineTable.length; i++) {
		
		if (machine == machineTable[i][0]) {
			site = machineTable[i][2]
			cell = machineTable[i][3]
			break
		}
	}
	
	for (i = 0; i < cellTable.length; i++) {
		
		if (cell == cellTable[i][0]) {
			site = cellTable[i][2]
			break
		}
	}
	
	
	
	
	for (i = 0, j = 1; i < machineTable.length; i++) {
		
		if ((cell == machineTable[i][3]) || ((cell == '') && ((site == machineTable[i][2]) || (site == '')))) {
			machineSelect.options[j] = new Option(machineTable[i][1], machineTable[i][0])
		
			if (machine == machineTable[i][0]) {
				machineSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	for (i = 0, j = 1; i < cellTable.length; i++) {
		
		if ((site == cellTable[i][2]) || ((site == '') || (site == '100'))) {
			
			cellSelect.options[j] = new Option(cellTable[i][1], cellTable[i][0])
			
			if (cell == cellTable[i][0]) {
				cellSelect.options[j].selected = true
			}
			
			j++
		}
	}
	
	if (siteSelect != null)
	{
		for (i = 0, j = 1; i < siteTable.length; i++) {
			siteSelect.options[j] = new Option(siteTable[i][1], siteTable[i][0])
			
			if (site == siteTable[i][0]) {
				siteSelect.options[j].selected = true
			}
			
			j++
		}
	}
}

function toProduceFormOnSiteChange() {
	toProduceForm.cell.value = ''
	toProduceForm.machine.value = ''
	toProduceForm.site.value = toProduceForm.site_select.value
	toProduceForm.submit();
}

function toProduceFormOnCellChange() {
	toProduceForm.site.value = ''
	toProduceForm.machine.value = ''
	toProduceForm.cell.value = toProduceForm.cell_select.value
	toProduceForm.submit();
}

function toProduceFormOnMachineChange() {
	toProduceForm.site.value = ''
	toProduceForm.cell.value = ''
	toProduceForm.machine.value = toProduceForm.machine_select.value
	toProduceForm.submit();
}


/*

////LOADER


*/

function start_loading()
{
	document.getElementById('loader').style.display = 'block';
}

function stop_loading()
{
	document.getElementById('loader').style.display = 'none';
}

/*
	Sales admin
*/

function salesAdminInformationFormFill()
{
	var salesAdminSelect, salesAdmin 
	
	salesAdmin = mainForm.id.value
	salesAdminSelect = mainForm.sales_admin_select
	
	clearHtmlSelect(salesAdminSelect)

	for (i = 0, j = 1; i < salesAdminTable.length; i++) {
		salesAdminSelect.options[j] = new Option(salesAdminTable[i][1], salesAdminTable[i][0])
		
		if (salesAdmin == salesAdminTable[i][0]) {
			salesAdminSelect.options[j].selected = true
		}
		
		j++
	}
}

function salesAdminInformationFormOnSalesAdminChange()
{
	mainForm.id.value = mainForm.sales_admin_select.value
	mainForm.submit();
}
