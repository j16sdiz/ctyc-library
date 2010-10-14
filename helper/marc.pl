#!/opt/bin/perl
use Data::Dumper;
use ZOOM;
use IPC::Open2;
use MARC::Record;
use utf8;
use JSON;
use strict;

my $conn = new ZOOM::Connection(
	$ARGV[0],
	210,
	databaseName => "INNOPAC",
	);
my $rs = $conn->search_pqf('@attr 1=7 ' . $ARGV[1]);
my %detail = ( title => [], author => [], publisher => [], publishDate => [], edition => [] );

my $n = $rs->size();
for (my $i = 0 ; $i < $n && $i < 3 ; $i++) {

my $raw = $rs->record($i)->raw();
my $marc = new_from_usmarc MARC::Record($raw);

if ($marc->encoding() eq "MARC-8") {
	my $pid = open2(\*CHLD_OUT, \*CHLD_IN, '/opt/bin/yaz-marcdump', '-f', 'MARC-8', '-t', 'UTF-8', '-o', 'marc', '/proc/self/fd/0');
	binmode CHLD_IN;
	binmode CHLD_OUT, ":utf8";
	print CHLD_IN $raw;
	close CHLD_IN;

	$marc = new_from_usmarc MARC::Record(<CHLD_OUT>);
}

push @{$detail{'title'}}, $marc->title_proper() if (defined $marc->title_proper());
push @{$detail{'author'}}, $marc->author() if (defined $marc->author());
push @{$detail{'edition'}}, $marc->edition() if (defined $marc->edition());
push @{$detail{'publishDate'}}, $marc->publication_date() if (defined $marc->publication_date());
push @{$detail{'publisher'}}, $marc->subfield('260', 'b') if (defined $marc->subfield('260', 'b'));

foreach my $f ($marc->field("880")) {
	# hack for 880
	my $tag = substr($f->subfield('6'), 0, 3);
	my $id = '880' . substr($f->subfield('6'), 3, 3);

	foreach ($marc->field($tag)) {
		$marc->delete_fields($_) if ($_->subfield('6') eq $id);
	}

	$f->{_tag} = $tag;
	$f->delete_subfield(code => '6');
}

push @{$detail{'title'}}, $marc->title_proper() if (defined $marc->title_proper());
push @{$detail{'author'}}, $marc->author() if (defined $marc->author());
push @{$detail{'edition'}}, $marc->edition() if (defined $marc->edition());
push @{$detail{'publishDate'}}, $marc->publication_date() if (defined $marc->publication_date());
push @{$detail{'publisher'}}, $marc->subfield('260', 'b') if (defined $marc->subfield('260', 'b'));
}

while(my ($key, $value) = each(%detail)) {
	my %hash = map {
		$_ = pack "U0C*", unpack "C*";
		$_ =~ s/^\s+//;
		$_ =~ s/\s*[\.\/,]*\s*$//;
		$_, 1;
	} @{$value};
	$detail{$key} = [ keys %hash ];
}

print encode_json(\%detail);
