const Storage = {
    /**
     * Get data by key
     */
    get(key:string, defaultValue : any) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error("LocalStorage get error:", error);
            return defaultValue;
        }
    },

    /**
     * Set data (replace all)
     */
    set(key:string, value : any) {
        localStorage.setItem(key, JSON.stringify(value));
    },

    /**
     * Add item (push or update if exists)
     */
    add(key:string, newItem :any, identifier = "id") {
        const items = this.get(key);

        const index = items.findIndex(
            (item : any) => item[identifier] === newItem[identifier]
        );

        if (index !== -1) {
            // update existing
            items[index] = {
                ...items[index],
                ...newItem,
            };
        } else {
            items.push(newItem);
        }

        this.set(key, items);
        return items;
    },

    /**
     * Update item by identifier
     */
    update(key : string, identifierValue : any, updatedData : any, identifier = "id") {
        const items = this.get(key);

        const updatedItems = items.map((item : any) =>
            item[identifier] === identifierValue
                ? { ...item, ...updatedData }
                : item
        );

        this.set(key, updatedItems);
        return updatedItems;
    },

    /**
     * Delete item by identifier
     */
    remove(key : string, identifierValue : any, identifier = "id") {
        const items = this.get(key);

        const filteredItems = items.filter(
            (item : any) => item[identifier] !== identifierValue
        );

        this.set(key, filteredItems);
        return filteredItems;
    },

    /**
     * Clear all data by key
     */
    clear(key : any) {
        localStorage.removeItem(key);
    },
};

export default Storage;
