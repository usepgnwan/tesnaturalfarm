export interface product {
    id : number
    title : string
    description : string
    stock : number
    harga: number
    foto : string
}

export interface datacart {
    id : number
    total : number
    harga : number
    data : product
}


 