
export function debounceValue<T extends (...args: any[]) => any>(
  fn: T,
  delay: number
): (...args: Parameters<T>) => void {
  let timeout: ReturnType<typeof setTimeout>;

  return (...args: Parameters<T>) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      fn(...args);
    }, delay);
  };
}


export const useGlobal = () => {

    const toRupiah = (n: number) => {
        return new Intl.NumberFormat('id-ID').format(n);
    };

    const formatDate = (date: string) => {
          const d = new Date(date);

          return d.toLocaleString('id-ID', {
                          day: '2-digit',
                          month: 'short',
                          year: 'numeric',
                          hour: '2-digit',
                          minute: '2-digit',
                          second: '2-digit',
                      });
    };
    return {
        toRupiah,
        formatDate,  
    }
}
