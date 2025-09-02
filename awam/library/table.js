class TableClass {
    constructor(tableId, rowsPerPage = 10) {
        this.tableId = tableId;
        this.data = [];
        this.displayData = [];
        this.displayLength = null;
        this.sortDirection = {};
        this.currentPage = 1;
        this.rowsPerPage = rowsPerPage;
        this.footer = null;
        this.pagination = null;
        this.totalRows = null;
        this.paginationOption = true;
        this.fontSizeCol = 1;
        this.fontSizeRow = 1;
        this.colBgColour = '#695CFE';
        this.colHighlightColour = 'rgba(37, 37, 37, 0.8)';
        this.colHeader = [];
        this.rowFontColour = 'rgb(31, 31, 31)';
    }

    setFontSizeCol(rem = 1) { this.fontSizeCol = rem; }

    setFontSizeRow(rem = 1) { this.fontSizeRow = rem; }
    
    setPagination(bool = true) { this.paginationOption = bool; }

    setColumnHeader(array = []) { this.colHeader = array; }

    setColBgColour(rgbCSS) { this.colBgColour = rgbCSS; }

    setColHighlightColour(rgbCSS) { this.colHighlightColour = rgbCSS }

    setRowFontColour(rgbCSS) {this.rowFontColour = rgbCSS }
    
    getData() { return this.displayData; }

    load(data = []) {
        this.data = data;
        this.displayData = data;
        this.displayLength = this.displayData.length;
        this.render(this.displayData);
    }

    render(data = []) {
        this.displayLength = data.length;

        const table = document.getElementById(this.tableId);
        table.innerHTML = '';
        table.className = 'table table-hover text-center align-middle fs-6 my-1';
        table.style.border = "0.1px solid rgb(224, 224, 224)";

        if (data.length === 0) {
            const noDataMessage = document.createElement('tr');

            // Clearkan border
            table.style.border = ""; 

            const td = document.createElement('td');
            td.colSpan = 100;
            td.className = 'py-4 text-center';

            td.textContent = "No record is available";
            noDataMessage.appendChild(td);
            table.appendChild(noDataMessage);

            if (this.footer) {
                this.footer.className = this.displayData.length > 0 ? 'd-block' : 'd-none';
            }

            return;
        }

        this.colHeader = this.colHeader.length === 0 ? Object.keys(this.data[0]) : this.colHeader;

        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        // Create column headers
        this.colHeader.forEach((header, index) => {
            const th = document.createElement('th');
            th.className = 'd-flex-inline align-middle border border-start';
            th.style = `font-size: ${this.fontSizeCol}rem; cursor: pointer; color: white;`;
            th.style.background = `${this.colBgColour}`;
            th.innerHTML = header;

            th.addEventListener("mouseover", () => {
                th.style.boxShadow = `inset 0 0 5px ${this.colHighlightColour}`;
            });
        
            th.addEventListener("mouseout", () => {
                th.style.boxShadow = ""; 
            });

            th.addEventListener('click', () => {
                this.sort(index);
            });
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Create rows
        const tbody = document.createElement('tbody');

        this.paginatedData().forEach(row => {
            const tr = document.createElement('tr');
            
            this.colHeader.forEach((key , header) => {
                const td = document.createElement('td');
                td.className = "align-middle"
                td.style = `font-size: ${this.fontSizeRow}rem; color: ${this.rowFontColour};`;

                if (typeof row[header] === 'object' && row[header] !== null && row[header].display) {
                    td.innerHTML = row[header].display;
                } else {
                    td.innerHTML = row[header];
                }

                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);

        // Kosongkan kejap (tengah cari jalan)
        // const showing = document.getElementById(`showing-value-${this.tableId}`);
        
        // if (showing) {
        //     showing.textContent = this.displayData.length > 0
        //         ? `Memaparkan ${this.displayData.length} rekod`
        //         : "Tiada rekod dijumpai";
        // }

        if (this.paginationOption === true && this.displayData.length > 0) {
            if (!this.pagination) {
                this.createPaginationControls(table);
            } else {
                this.updatePaginationControls();
            }
        }

        if (this.footer) {
            this.footer.className = this.displayData.length > 0 ? 'd-block' : 'd-none';
        }
    }

    search(input) {
        if (input.length > 0) {
            this.displayData = this.data.filter(row => {
                return Object.values(row).some(value =>
                    value.toString().toLowerCase().includes(input.toLowerCase())
                );
            });
            this.displayLength = this.displayData.length;
        } else {
            this.displayData = this.data;
            this.displayLength = this.displayData.length;
        }

        this.displayLength = this.displayData.length;
        this.currentPage = 1;
        this.render(this.displayData);
    }

    sort(columnName) {
        const direction = this.sortDirection[columnName] === 'asc' ? 'desc' : 'asc';
        this.sortDirection[columnName] = direction;

        this.displayData.sort((a, b) => {
            let valueA = a[columnName]; 
            let valueB = b[columnName];

            if (typeof valueA === 'object' && typeof valueB === 'object' && Date.parse(valueA.original) && Date.parse(valueB.original)) {
                console.log("date sort");
                return direction === 'asc' ? new Date(valueA.original) - new Date(valueB.original) : new Date(valueB.original) - new Date(valueA.original);  
            }

            if (typeof valueA === 'object' && typeof valueB === 'object' && !isNaN(parseFloat(valueA.original)) && !isNaN(parseFloat(valueB.original))) {
                console.log("numeric object sort");
                return direction === 'asc' ? parseFloat(valueA.original) - parseFloat(valueB.original) : parseFloat(valueB.original) - parseFloat(valueA.original);
            }

            if (typeof valueA === 'object' && typeof valueB === 'object') {
                console.log("object sort");
                return direction === 'asc' ? valueA.original.localeCompare(valueB.original) : valueB.original.localeCompare(valueA.original);
            }
    
            if (typeof valueA === 'string' && typeof valueB === 'string') {
                console.log("string sort");
                return direction === 'asc' ? valueA.localeCompare(valueB) : valueB.localeCompare(valueA);
            } 
            
            if (typeof valueA === 'number' && typeof valueB === 'number') {
                console.log("num sort");
                return direction === 'asc' ? valueA - valueB : valueB - valueA;
            }

            return 0;
        });

        this.render(this.displayData);
    }

    paginatedData() {
        const start = (this.currentPage - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;
        
        return this.displayData.slice(start, end);
    }

    createPaginationControls(table) {
        this.footer = document.createElement('div');
        this.footer.className = 'row mt-3';

        this.pagination = document.createElement('div');
        this.pagination.className = 'col d-flex justify-content-between mt-3'
        this.footer.appendChild(this.pagination);

        const showing = document.createElement('p');
        showing.className = 'ms-1 pt-1 text-start';
        showing.id = `showing-value-${this.tableId}`;

        // Kosongkan kejap (tengah cari jalan)
        // showing.textContent = this.displayData.length > 0 ? `Memaparkan ${this.displayData.length} rekod` : "Tiada rekod tersedia untuk dipaparkan"; 

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        this.pagination.appendChild(showing);
        this.pagination.appendChild(ul);

        table.parentElement.appendChild(this.footer);

        if (this.displayData.length > 0) {
            this.updatePaginationControls();
        }
    }

    updatePaginationControls() {
        const totalPages = Math.ceil(this.displayData.length / this.rowsPerPage);
        const ul = this.pagination.querySelector('.pagination');

        ul.innerHTML = '';

        const createPageLink = (pageNum, text) => {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${this.currentPage === pageNum ? 'active' : ''}`;

            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.innerHTML = text;

            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                
                this.currentPage = pageNum;
                this.render(this.displayData);
            });

            pageItem.appendChild(pageLink);
            ul.appendChild(pageItem);
        }

        if (this.currentPage > 2) {
            createPageLink(1, 1);
            const ellipsis1 = document.createElement('li');
            ellipsis1.className = 'page-item disabled';
            ellipsis1.innerHTML = '<span class="page-link"><</span>';

            ul.appendChild(ellipsis1);
        }

        let startPage = Math.max(1, this.currentPage - 1);
        let endPage = Math.min(totalPages, this.currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            createPageLink(i, i);
        }

        if (this.currentPage < totalPages - 1) {
            const ellipsis2 = document.createElement('li');
            ellipsis2.className = 'page-item disabled';
            ellipsis2.innerHTML = '<span class="page-link">></span>';

            ul.appendChild(ellipsis2);
            createPageLink(totalPages, totalPages);
        }
    }
}
