function dragAndDrop(element, destination) {
    class FakeDragEvent extends CustomEvent {
        dataTransfer;
    }
    function createFakeDragEvent(typeOfEvent) {
        const event = new FakeDragEvent(typeOfEvent);
        event.dataTransfer = {
            data: {},
            setData: function (key, value) {
                this.data[key] = value;
            },
            getData: function (key) {
                return this.data[key];
            }
        };
        return event;
    }
    const dragStartEvent = createFakeDragEvent('dragstart');
    const dropEvent = createFakeDragEvent('drop');
    const dragEndEvent = createFakeDragEvent('dragend');
    element.dispatchEvent(dragStartEvent);
    dropEvent.dataTransfer = dragStartEvent.dataTransfer;
    destination.dispatchEvent(dropEvent);
    dragEndEvent.dataTransfer = dropEvent.dataTransfer;
    element.dispatchEvent(dragEndEvent);
}
