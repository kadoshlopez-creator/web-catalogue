class DashboardManager {
    constructor() {
        this.container = document.getElementById('dashboard-grid');
        this.widgets = document.querySelectorAll('.dashboard-widget');
        this.layout = {};
        
        if (this.container) {
            this.initDragAndDrop();
        }
    }

    initDragAndDrop() {
        let draggedElement = null;

        this.widgets.forEach(widget => {
            const handle = widget.querySelector('.handle');
            if (handle) {
                widget.setAttribute('draggable', true);
                
                widget.addEventListener('dragstart', (e) => {
                    draggedElement = widget;
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', widget.dataset.widgetId);
                    setTimeout(() => widget.classList.add('opacity-50'), 0);
                });

                widget.addEventListener('dragend', () => {
                    draggedElement.classList.remove('opacity-50');
                    draggedElement = null;
                    this.saveLayout();
                });

                widget.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    return false;
                });

                widget.addEventListener('dragenter', (e) => {
                    e.preventDefault();
                    if (widget !== draggedElement) {
                        widget.classList.add('border-blue-400', 'border-dashed');
                    }
                });

                widget.addEventListener('dragleave', () => {
                    widget.classList.remove('border-blue-400', 'border-dashed');
                });

                widget.addEventListener('drop', (e) => {
                    e.stopPropagation();
                    widget.classList.remove('border-blue-400', 'border-dashed');
                    
                    if (draggedElement && draggedElement !== widget) {
                        const allWidgets = [...this.container.querySelectorAll('.dashboard-widget')];
                        const draggedIndex = allWidgets.indexOf(draggedElement);
                        const dropIndex = allWidgets.indexOf(widget);

                        if (draggedIndex < dropIndex) {
                            widget.parentNode.insertBefore(draggedElement, widget.nextSibling);
                        } else {
                            widget.parentNode.insertBefore(draggedElement, widget);
                        }
                    }
                    return false;
                });
            }
        });
    }

    saveLayout() {
        const order = [...this.container.querySelectorAll('.dashboard-widget')].map(el => el.dataset.widgetId);
        
        fetch('/admin/dashboard/preferences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                layout_json: order
            })
        }).then(res => {
            if (!res.ok) console.error('Failed to save layout');
        }).catch(err => console.error(err));
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.dashboardManager = new DashboardManager();
});
