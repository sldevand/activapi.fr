class VirtualScroll extends HTMLElement {
    constructor() {
        super()
        this.root = this.attachShadow({ mode: "open" });
        this.wrapper = document.createElement("div");
        this.data = [];
        this.rowHeight;
        this.totalContentHeight;
        this.viewportHeight = 300;
    }
    connectedCallback() {
        this.root.appendChild(this.wrapper);
    }

    initCreateRowCallback(createRowCallback) {
        this.createRowCallback = createRowCallback;
    }

    setViewportHeight(height) {
        this.viewportHeight = height;
    }

    setData(data) {
        this.data = data;
        this.render();
    }

    clear() {
        Array.from(this.wrapper.children).forEach(c => c.remove())
    }

    render(scrollTop = 0) {
        this.clear();
        if (!this.data.length) {
            let nothingFound = document.createElement("div");
            nothingFound.innerHTML = `<span>Nothing found</span>`;
            this.wrapper.appendChild(nothingFound);
            return;
        }
        if (!this.rowHeight) {
            this.rowHeight = this.getRowHeight();
            this.totalContentHeight = this.data.length * this.rowHeight;
        }

        let startNode = Math.floor(scrollTop / this.rowHeight) - 1;
        startNode = Math.max(0, startNode);
        const offsetY = startNode * this.rowHeight;

        this.root.innerHTML = `
        <style>
            :host {
                height: ${this.totalContentHeight}px;
                transform:translateY(${offsetY}px);
            }
        </style>`;


        let visibleNodesCount = Math.ceil(this.viewportHeight / this.rowHeight) + 2;
        const visibleChildren = this.data.slice(startNode, startNode + visibleNodesCount);
        visibleChildren.forEach(rowData => {
            let virtualScrollRow = this.createRowCallback(rowData);
            this.wrapper.appendChild(virtualScrollRow);
        });

        this.root.appendChild(this.wrapper);
    }

    getRowHeight() {
        let rowData = this.data[1];
        let virtualScrollRow = this.createRowCallback(rowData);
        this.wrapper.appendChild(virtualScrollRow);
        let rowHeight = this.wrapper.offsetHeight;
        this.clear();
        return rowHeight;
    }
}

customElements.define("virtual-scroll", VirtualScroll);

export default VirtualScroll;