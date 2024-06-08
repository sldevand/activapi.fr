class VirtualScrollRow extends HTMLElement {
    constructor(data, cssStyle) {
        super();
        this.root = this.attachShadow({ mode: "open" });
        this.wrapper = document.createDocumentFragment();
        this.data = data;
        this.cssStyle = cssStyle;
    }
    buildStyle() {
        return this.cssStyle;
    }

    connectedCallback() {
        this.root.innerHTML = this.buildStyle();
        this.setData(this.data);
        this.root.appendChild(this.wrapper);
    }

    setData(data) {
        this.data = data;
        this.clear();
        this.render();
    }

    clear() {
        Array.from(this.wrapper.children).forEach(c => c.remove())
    }

    render() {
        for (const key in this.data) {
            if (Object.hasOwnProperty.call(this.data, key)) {
                let span = document.createElement("span");
                span.innerText = this.data[key];
                this.wrapper.appendChild(span)
            }
        }
    }
}

customElements.define("virtual-scroll-row", VirtualScrollRow);
export default VirtualScrollRow;
