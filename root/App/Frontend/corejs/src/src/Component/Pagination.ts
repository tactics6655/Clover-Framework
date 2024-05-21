export class Pagination {
    
    private point: number;
    private listCount: number;
    private lastPage: number;
    private pageMargin: number;
    private firstPage: number;
    private pageCount: number;

    constructor (currentPage = 1, listCount = 10, documentCount = 10) {
        this.listCount = listCount;
        
        let pageMargin = 0;
        let firstPage = 0;
        let lastPage = Math.ceil(documentCount / listCount);
        let halfPageCount = Math.ceil(listCount / 2);
        
        lastPage = (lastPage < 0) ? 1 : lastPage;
        
        if (lastPage > listCount) {
            if (currentPage > lastPage - (listCount - 1)) {
                pageMargin = lastPage - listCount;
                firstPage = pageMargin < listCount ? 0 : -1;
            } else if (currentPage > halfPageCount) {
                pageMargin = currentPage - (halfPageCount);
                firstPage = pageMargin > listCount ? 0 : -1;
            }
            
            if (currentPage > lastPage - (listCount - 1) && currentPage < lastPage - (halfPageCount - 1)) {
                pageMargin = currentPage - halfPageCount;
                firstPage = pageMargin > listCount ? 0 : -1;
            }
        }
        
        this.point = 0;
        this.pageMargin = pageMargin;
        this.firstPage = firstPage;
        this.lastPage = lastPage;
        this.pageCount = lastPage;
        this.listCount = listCount;
    }

    getLastPage () {
        return this.pageCount;
    }

    getCurrentPage () {
        return (this.pageMargin + this.firstPage + this.point);
    }
    
    hasNextPage () {
        let page = this.firstPage + ++this.point;

        if (page > this.listCount || this.getCurrentPage() > this.lastPage) {
            this.point = 0;
            
            return false;
        }
        
        return true;
    }
}