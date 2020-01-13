create function latin1ToUtf8 ( str text )
	returns text as $$
declare 
res text;
begin
	res := 
	convert_from(
	convert(
	(select convert_to(str, 'UTF-8'))
	, 'utf-8', 'latin-1')
	, 'utf-8');
	return res;
end; $$
language plpgsql;

select latin1ToUtf8('Viá»t Nam')